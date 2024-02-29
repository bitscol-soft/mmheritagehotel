<script>
    $(document).on('ready', function() {
        load_items();
        load_products();
    });


    var item_row = 0;
    var items = [];

    // insert new row
    function insert_Row(el) {
        // first delete add item
        $(el).parents("tr").remove();


        // add new item row
        var r = document.getElementById('purchase_table').insertRow();

        var c1 = r.insertCell(0);
        var c2 = r.insertCell(1);
        var c3 = r.insertCell(2);
        var c4 = r.insertCell(3);
        var c5 = r.insertCell(4);
        var c6 = r.insertCell(5);



        var inputs =
            '<input type="hidden" name="issue_number[]" class="issue_number_input"><input type="hidden" name="source[]" class="source_input"><input type="hidden" name="issue_rates[]" class="issue_rate_input"><input type="hidden" name="issue_quantities[]" class="issue_quantity_input">';

        // populate product
        c1.innerHTML = '<select name="item_id[]" class="form-control item item' + item_row +
            ' chosen-select" onchange="load_item_stock(this)" id="abc' + (item_row + 1) +
            '" data-placeholder="-Select Item-"><option></option></select>';

        c2.innerHTML = '<input type="text" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />';

        c3.innerHTML = '<input type="text" id="item_available_quantityq"' + (item_row + 1) +
            ' name="item_available_quantity[]" class="form-control current_stock" readonly="readonly" />';

        c4.innerHTML =
            '<input onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" type="text" id="q"' +
            (item_row + 1) + ' name="quantity[]" onkeyup="checkQtyLimit(this)" class="form-control quantity"/>';
        c5.innerHTML = '<input type="text" class="form-control" name="remarks[]" value="">';

        c6.innerHTML =
            '<button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button>';


        // again add "+ Add New" Button
        var markup =
            '<tr><td colspan="11" style="text-align: right;"><button type="button" onclick="insert_Row(this)" class="btn btn-xs btn-inverse add_row r-btnAdd"> + Add New </button></td></tr>';
        $("table#purchase_table tbody").append(markup);


        $('.item' + item_row).empty();
        $('.item' + item_row).append('<option></option>');
        $.each(items, function(id, name) {
            $('.item' + item_row).append('<option value="' + id + '">' + name + '</option>');
        });

        // increment id
        item_row++;
        // $('.select2').select2()
        chosenTrigger()
    }

    // delete specific row
    function removeRow(el) {
        var item_row = $('#purchase_table tr').length;
        if (item_row > 4) {
            $(el).parents("tr").remove();
        }

    }





    // load items to the select box when change company
    function load_items(element) {
        var id = $(element).val() || element;
        var row = $(element).closest('tr');

        $.ajax({
            url: "/restaurant/inventory/GetMetrial",
            type: 'GET',
            data: 'id=' + id,
            success: function(res) {
                $('.item').empty();
                $('.item').append('<option></option>');
                $.each(res['items'], function(id, name) {
                    $('.item').append('<option value="' + id + '">' + name + '</option>').trigger(
                        'chosen:updated');
                });
                items = res['items'];
            }
        });
    }
    // load items to the select box when change company
    function load_products(element) {
        var id = $(element).val() || element;
        var row = $(element).closest('tr');

        $.ajax({
            url: "/restaurant/inventory/GetProducts",
            type: 'GET',
            data: 'id=' + id,
            success: function(res) {
                $('.item2').empty();
                $('.item2').append('<option></option>');
                $.each(res['products'], function(id, name) {
                    $('.item2').append('<option value="' + id + '">' + name + '</option>').trigger(
                        'chosen:updated');
                });
                products = res['products'];
            }
        });
    }


    // load item unit and current stock when change item
    function load_item_stock(element) {
        var id = $(element).val();

        var count = 0;
        $.each($('.item'), function(el) {
            if (id == $(this).val()) {
                count++;
            }
            if (count > 1) {
                alert('This item already selected')
                $(this).val('')
            }
        });

        var row = $(element).closest('tr');

        if (count <= 1) {

            $.ajax({

                url: "/restaurant/inventory/get-matrial-details",
                type: 'GET',
                data: 'id=' + id,
                success: function(res) {
                    console.log(res);

                    row.find('.material_item_unit').val(res['mat_item_unit']);
                    row.find('.material_unit_id').val(res['mat_unit_id']);
                    row.find('.material_current_stock').val(res['mat_current_stock']);
                    row.find('.material_current_price').val(res['mat_price']);
                    row.find('.material_category_id').val(res['mat_category_id']);

                }
            });
        }
    }
    // load item unit and current stock when change item
    function load_product_stock(element) {
        var id = $(element).val();

        var count = 0;
        $.each($('.item2'), function(el) {
            if (id == $(this).val()) {
                count++;
            }
            if (count > 1) {
                alert('This item already selected')
                $(this).val('')
            }
        });

        var row = $(element).closest('tr');

        if (count <= 1) {

            $.ajax({
                url: "/restaurant/inventory/get-item-details",
                type: 'GET',
                data: 'id=' + id,
                success: function(res) {

                    row.find('.item_unit').val(res['item_unit']);
                    row.find('.item_current_stock').val(res['current_stock']);
                }
            });
        }
    }

    // Checked Quentity
    function checkQtyLimit(object) {

        let mat_current_stock = Number($(object).closest('tr').find('.material_current_stock').val() | 0)

        let qty = Number($(object).val() | 0)

        if (qty > mat_current_stock) {
            showAlertMessage('Limit Up!', 2000)
            $(object).val(0)
        }

    }

    // Checked Quentity
    function checkItemQtyLimit(object) {

        let item_current_stock = Number($(object).closest('tr').find('.item_current_stock').val() | 0)

        let qty = Number($(object).val() | 0)

        if (qty > item_current_stock) {
            showAlertMessage('Limit Up!', 2000)
            $(object).val(0)
        }
    }
</script>


{{-- chosen select --}}
{{-- <script type="text/javascript">
    $(() => chosenTrigger())

    function chosenTrigger() {
        jQuery(function($) {

            if (!ace.vars['touch']) {
                $('.filter').chosen({
                    allow_single_deselect: true
                });
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.filter').each(function() {
                            var $this = $(this);
                            $this.next().css({
                                'width': $this.parent().width()
                            });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.filter').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                });
            }


            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({
                    allow_single_deselect: true
                });
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({
                                'width': '220px'
                            });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                });
            }

        })
    }



</script> --}}
