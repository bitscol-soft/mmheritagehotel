@php
if (Cookie::get('booking_cart')) {

    $get_cookie = \stripslashes(Cookie::get('booking_cart'));
    $cart_data  = \json_decode($get_cookie, true);

}else {
    $cart_data = array();
}

@endphp

<li class="s-bar">
    <div class="search">
        <input class="search_box" type="checkbox" id="search_box">
        <label class="icon-search" for="search_box">
            <span class="fa fa-cart-arrow-down" aria-hidden="true"></span>
            <span id="cart_count">{{ count($cart_data) }}</span>
        </label>
        <div class="search_form">
            {{-- <form action="#" method="post">
                <input type="search" name="Search" placeholder=" " required=" " />
                <input type="submit" value="Search">
            </form> --}}




            {{-- Booking Cart Section --}}

            <div class="cart">
                <div class="booking-cart-body">
                    <ul class="cart-list">

                        @php
                            $sub_total = 0;
                        @endphp

                        @foreach ($cart_data as $booking)

                            @php
                                $main_price = $booking['item_price'];
                                $sub_total  += $main_price;
                            @endphp

                            <li class="cart-body cart-items">

                                <div class="booking-img">
                                    <img class="img-responsive" src="{{ asset($booking['item_img_path'].$booking['item_img']) }}" alt="">
                                </div>

                                <div class="booking-info">
                                    <input id="booking_id" type="hidden" name="" value="{{ $booking['item_id'] }}">
                                    <h1>{{ $booking['item_name'] }}</h1>
                                    <p class="booking-date">Check in Date  :   <span>{{ $booking['check_in'] }}</span></p>
                                    <p class="booking-date">Check out Date :  <span>{{ $booking['check_out'] }}</span></p>
                                    <p class="booking-date">Nights : <span>{{ $booking['nights'] }}</span></p>
                                </div>

                                <input id="price" type="hidden" value="{{ $booking['item_price'] }}">
                                <div class="booking-price">{{ number_format($booking['item_price'], 2) }} &#x09F3;</div>
                                <div class="booking-close" onclick="removeItem(this,{{ $booking['item_id'] }})"><i class="fa fa-times"></i></div>
                            </li>
                        @endforeach

                        <li class="cart-body subtotal-body">
                            <div class="sub-total">
                                <div class="row">
                                    <div class="col-md-6"><p class="cart-footer-title">Subtotal :</p></div>
                                    <div class="col-md-6"><p class="cart-total-amount text-right">RM {{ number_format($sub_total, 2) }}</p></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="checkout text-right">
                        <a href="{{ route('booking.cart') }}" class="btn btn-checkout">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>

@push('custom_js')

@endpush
