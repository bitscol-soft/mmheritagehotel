$(document).ready(function () {

    // Add to Cart Ajax Function

    $('#add-to-cart').on('click', function (e) {

        e.preventDefault();

        var check_in       =    $('.book-form').find('.check_in').val();
        var check_out      =    $('.book-form').find('.check_out').val();
        var guest_capacity =    $('.guest_capacity').val();

        var start_date = new Date(check_in);
        var end_date   = new Date(check_out);

        diff    = new Date(end_date - start_date),
        nights  = diff/1000/60/60/24;

        var category_id = $('#room_category option:selected').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            method: "POST",
            url: "/add-to-cart",
            data: {
                'check_in_date'  : check_in,
                'check_out_date' : check_out,
                'category_id'    : category_id,
                'nights'         : nights,
                'guest_capacity' : guest_capacity,
            },

            success: function (data) {



                if (data.return == 1) {
                    var base_url  = $('#base_url').val();
                    var cart_body = `<li class="cart-body cart-items">
                                    <div class="booking-img">
                                        <img class="img-responsive" src="${base_url +'/'+ data.booking_item.item_img_path + data.booking_item.item_img}" alt="">
                                    </div>
                                    <div class="booking-info">
                                        <input id="booking_id" type="hidden" name="" value="${data.booking_item.item_id}">
                                        <h1>${data.booking_item.item_name}</h1>
                                        <p class="booking-date">Check in Date  :   <span>${data.booking_item.check_in}</span></p>
                                        <p class="booking-date">Check out Date :  <span>${data.booking_item.check_out}</span></p>
                                        <p class="booking-date">Nights : <span>${data.booking_item.nights}</span></p>
                                    </div>
                                    <input id="price" type="hidden" value="${data.booking_item.item_price}">
                                    <div class="booking-price">${data.booking_item.item_price} &#x09F3;</div>
                                    <div class="booking-close"><i onclick="removeItem(this,${data.booking_item.item_id})" class="fa fa-times"></i></div>
                                </li>`

                    toastr.success(data.status);
                   $('.cart-list').prepend(cart_body);
                   $('#cart_count').html(data.cart_count);
                   totalPrice();

                }else{
                    toastr.error(data.status);
                }
            }
        });

    });


});

// Cart unit Price function

function totalPrice(){
    var sub_total = 0;

    $('.cart-list > .cart-items').each(function(index, cart) {

        var cart_item  = cart;
        var price      = $(cart_item).find('#price').val();
        sub_total     += parseFloat(price);

    });

    $(".cart-total-amount").html(sub_total.toFixed(2) + '৳');

}

// Remove Cart Items From Header

function removeItem(object, id){
    $.ajax({
        method: "get",
        url: "/remove-to-cart",
        data: {
            'booking_id' : id
        },

        success: function (data) {
            $(object).closest('.cart-items').remove();
            $('#cart_count').html(data.cart_count);
            toastr.success(data.status);
            totalPrice();
        }
    });
}

// Remove Itesms from Checkout

function bookingRemove(object, id){

    $.ajax({
        type: "get",
        url: "/remove-to-cart",
        data: {
            'booking_id' : id
        },

        success: function (data) {

            $(object).closest('.cart-items').remove();
            $('#cart_count').html(data.cart_count);
            toastr.success(data.status);
        }
    });
}



