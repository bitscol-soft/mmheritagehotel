@extends('layouts.master')


@section('title', 'Sale Return Create')



@section('page-header')
    <i class="fa fa-plus-circle"></i> Sale Return Create
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />


    <style>
        .borderRemove {
            border-top: none;
        }

        body {
            counter-reset: section;
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }

        select:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        select:invalid[multiple] {
            margin-top: 15px !important;
        }
    </style>
@endpush






@section('content')
    <div class="row">
        <div class="col-sm-12 col-sm-offset-0">

            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>


                    <div class="widget-toolbar">
                        <a href="{{ route('acc-sale-returns.index') }}" ><i class="fa fa-list-alt"></i> List</a>
                    </div>

                </div>





                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('acc-sale-returns.store') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            @include('partials._alert_message')

                            <input hidden name="account_id" value="{{ $account->id }}">


                            <div class="row">







                                <!-- Companies -->
                                <div class="col-sm-3 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">Company</span>

                                        <select required name="company_id" class="chosen-select-100-percent select-company" data-placeholder="- Select Company -">
                                            <option></option>

                                            @foreach($companies as $id => $name)

                                                @if(count($companies) > 1)
                                                    <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @else
                                                    <option value="{{ $id }}" selected>{{ $name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>






                                <!-- Customer -->
                                <div class="col-sm-3 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Customer<span class="text-danger">*</span>
                                        </span>
                                        <select required name="customer_id" class="chosen-select-100-percent select-customer" data-placeholder="- Select Customer -">
                                            <option></option>
                                            @foreach($customers as $id => $name)
                                                <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>





                                <!-- Date -->
                                <div class="col-sm-2 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Date<span class="text-danger">*</span>
                                        </span>
                                        <input name="date" class="form-control date-picker" id="id-date-picker-1" type="text" value=" {{ old('date') ?:  date('Y-m-d') }}" data-date-format="yyyy-mm-dd">
                                    </div>
                                </div>






                                <!-- Invoice -->
                                <div class="col-sm-4 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Invoice<span class="text-danger">*</span>
                                        </span>
                                        <select required name="sale_id" class="chosen-select-100-percent select-invoice" data-placeholder="- Select Invoice -">
                                            <option></option>
                                            
                                        </select>
                                    </div>
                                </div>








                                <!-- PRODUCT RETURN INFORMATION -->
                                <div class="col-sm-12 mt-3">
                                    <h4>Product Return Information</h4>
                                    <table id="myTable" class="table table-bordered order-list">



                                        <!-- head -->
                                        <thead>
                                            <tr>
                                                <td width="40px;">SL.</td>
                                                <td style="width: 25%">Product Name</td>
                                                <td class="text-center" width="120px;">Unit</td>
                                                <td class="text-center" width="200px;">Condition</td>
                                                <td class="text-center" width="120px;">Price</td>
                                                <td class="text-center" width="120px;">Returnable Qty</td>
                                                <td class="text-center" width="120px;">Quantity</td>
                                                <td class="text-center" width="120px;">Subtotal</td>
                                            </tr>
                                        </thead>



                                        <!-- body -->
                                        <tbody class="returnable-sale-items">
                                           
                                            
                                        </tbody>




                                        <!-- footer -->
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-right item-serial">Total</td>
                                                <td>
                                                    <input readonly name="return_total_qty" value="{{ old('return_total_qty') }}" class="return-total-qty text-center form-control">
                                                </td>
                                                <td>
                                                    <input readonly name="return_total_amount" value="{{ old('return_total_amount') }}" class="return-total-amount text-center form-control">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>







                                <!-- PRODUCT EXCHANGE INFORMATION -->
                                <div class="col-sm-12 mt-3">
                                    <h4>Product Exchange Information</h4>





                                   <div class="row">
                                        <!-- Product -->
                                        <div class="col-sm-4 my-1">
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm">
                                                    Product
                                                </span>
                                                <select class="form-control chosen-select-100-percent select-product" >
                                                    <option></option>
                                                    @foreach($products as $prod)
                                                        <option value="{{ $prod->id }}" data-unit="{{ optional($prod->unit)->name }}" data-price="{{ $prod->selling_price > 0 ? $prod->selling_price : $prod->purchase_price }}">{{ $prod->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <!-- Product -->
                                        <div class="col-sm-3 my-1">
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm">
                                                    Price
                                                </span>
                                                <input class="form-control only-number text-center input-price" placeholder="Sale Price">
                                            </div>
                                        </div>


                                        <!-- Product -->
                                        <div class="col-sm-3 my-1">
                                            <div class="input-group">
                                                <span class="input-group-addon input-sm">
                                                    Quantity
                                                </span>
                                                <input class="form-control only-number text-center input-qty" placeholder="Exchange Quantity">
                                            </div>
                                        </div>



                                        <!-- ACTION -->
                                        <div class="col-sm-2 my-1">
                                            <button type="button" class="btn btn-sm btn-primary add-exchange-prduct-btn">
                                                <i class="fa fa-plus"></i> Add
                                            </button>
                                        </div>
                                   </div>


                                    <table id="myTable" class="table table-bordered order-list">



                                        <!-- head -->
                                        <thead>
                                            <tr>
                                                <td width="40px;">SL.</td>
                                                <td style="width: 25%">Product Name</td>
                                                <td class="text-center" width="120px;">Unit</td>
                                                <td class="text-center" width="120px;">Price</td>
                                                <td class="text-center" width="120px;">Quantity</td>
                                                <td class="text-center" width="120px;">Subtotal</td>
                                                <td style="width: 5%"></td>
                                            </tr>
                                        </thead>



                                        <!-- body -->
                                        <tbody class="exchange-product-details">
                                           
                                            
                                        </tbody>




                                        <!-- footer -->
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="text-right">Total</td>
                                                <td>
                                                    <input readonly name="exchange_total_qty" value="{{ old('exchange_total_qty') }}" class="exchange-total-qty text-center form-control">
                                                </td>
                                                <td>
                                                    <input readonly name="exchange_total_amount" value="{{ old('exchange_total_amount') }}" class="exchange-total-amount text-center form-control">
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>





                                <div class="col-sm-12 mt-3">







                                    <!-- Total Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Total Amount</label>
                                                <div class="col-xs-12 col-sm-8">
                                                    <input name="grand_total_amount" value="" readonly class="grand-total-amount text-right form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Paid Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Paid Amount</label>
                                                <div class="col-xs-12 col-sm-8">
                                                    <input name="paid_amount" value="" autocomplete="off" class="paid-amount only-number calculate-paid total-credit text-right form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Due Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Due Amount</label>
                                                <div class="col-xs-12 col-sm-8 @error('end_date') has-error @enderror">
                                                    <input name="due_amount" value="" readonly class="due-amount total-credit text-right form-control">
                                                    @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    <!-- Action -->
                                    <div class="row">
                                        <div class="pull-right px-1">
                                            <button type="submit" class="btn btn-sm btn-success save-btn">
                                                <i class="fa fa fa-save"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>


    <script>


        const enableField           = $('.quantity-enable')
        const enableDiscountField   = $('.dicount-enable')
        const draftValue            = $('.draft-value')

        const url_returnable_sale_invoices  = "{{ route('acc-returnable-sale-invoices') }}"
        const url_returnable_sale_items     = "{{ route('acc-returnable-sale-items') }}"


        $(document).on('keyup', '.return-price, .return-quantity', calculateReturnTotal)

        $(document).on('change', '.select-product', setProductPrice)

        $(document).on('click', '.add-exchange-prduct-btn', addExchangeProduct)

        $(document).on('click', '.remove-exchange-product-btn', removeExchangeItem)

        $(document).on('keyup', '.paid-amount', calculateGrandTotal)



        function removeExchangeItem()
        {
            $(this).closest('tr').remove()
            calculateExchangeTotal()
        }


        function addExchangeProduct()
        {
            let product_id      = $('.select-product').val()
            let product_name    = $('.select-product').find('option:selected').text()
            let unit            = $('.select-product').find('option:selected').data('unit')

            let price           = Number($('.input-price').val())
            let quantity        = Number($('.input-qty').val())

            if(product_id == '' || $('.input-price').val() == '' || $('.input-qty') == '')
            {
                alert('Please fill all fields')
                return false
            }

            let product_row = `
                <tr>
                    <td class="serial"></td>
                    <td>
                        ${product_name}
                    </td>
                    <td class="text-center">
                        ${unit}
                    </td>
                    <td>
                        <input type="text" readonly name="exchange_product_prices[${product_id}]" value="${price}" class="form-control text-center exchange-product-price only-number">
                    </td>
                    <td>
                        <input type="text" readonly name="exchange_product_qtys[${product_id}]" value="${quantity}" class="form-control text-center exchange-product-qty only-number">
                    </td>
                    <td>
                        <input type="text" readonly name="exchange_product_subtotals[${product_id}]" value="${price * quantity}" class="form-control text-center exchange-product-subtotal only-number">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-exchange-product-btn">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `

            $('.exchange-product-details').append(product_row)

            calculateExchangeTotal()

            $('.select-product').val('').trigger('chosen:updated')
        }





        function calculateExchangeTotal()
        {
            let subtotal        = 0
            let total_qty       = 0
            let total_amount    = 0

            $('div').find('.serial').each(function(index) {
                
                $(this).text(Number(index) + 1)

                let quantity  = Number($(this).closest('tr').find('.exchange-product-qty').val())
                let price    = Number($(this).closest('tr').find('.exchange-product-price').val())

                subtotal        += quantity * price
                total_qty       += quantity
                total_amount    += subtotal

                $(this).closest('tr').find('.exchange-product-subtotal').val(subtotal)
            })


            $('.exchange-total-qty').val(total_qty)
            $('.exchange-total-amount').val(total_amount)

            calculateGrandTotal()
        }



        function setProductPrice()
        {
            let product = $(this).find('option:selected')

            $('.input-price').val(product.data('price'))

            $('.input-qty').focus()
        }




        function calculateReturnTotal()
        {
            let subtotal        = 0
            let total_qty       = 0
            let total_amount    = 0

            $('div').find('.return-quantity').each(function() {
                
                let returnbale_qty  = Number($(this).closest('tr').find('.returnable-qty').val())
                let return_price    = Number($(this).closest('tr').find('.return-price').val())
                let return_qty      = Number($(this).closest('tr').find('.return-quantity').val())

                
                if(return_qty > returnbale_qty) {

                    alert('Limit Up')

                    $(this).closest('tr').find('.return-quantity').val(returnbale_qty)

                    return_qty = returnbale_qty
                }

                subtotal        += return_qty * return_price
                total_qty       += return_qty
                total_amount    += subtotal

                $(this).closest('tr').find('.return-subtotal').val(subtotal)
            })


            $('.return-total-qty').val(total_qty)
            $('.return-total-amount').val(total_amount)

            calculateGrandTotal()
        }



        function calculateGrandTotal()
        {
            let return_total_amount     = Number($('.return-total-amount').val())
            let exchange_total_amount   = Number($('.exchange-total-amount').val())
            let paid_amount             = Number($('.paid-amount').val())
            
            let grand_total_amount      = return_total_amount - exchange_total_amount

            $('.grand-total-amount').val(grand_total_amount)
            $('.due-amount').val(grand_total_amount - paid_amount)
        }


        $('.select-customer').change(function() {

            let customer_id = $(this).val()

            $('.select-invoice').empty().append('<option></option>').trigger('chosen:updated')

            if(customer_id != '')
            {
                $.get(url_returnable_sale_invoices, {
                    customer_id: customer_id
                })
                .then(function(data) {

                    $(data).each(function(index, item) {

                        $('.select-invoice').append(`<option value="${ item.id }">${ item.invoice_no }</option>`)
                    })
                    $('.select-invoice').trigger('chosen:updated')
                })
            }
        })








        $('.select-invoice').change(function() {

            let sale_id = $(this).val()


            if(sale_id != '')
            {
                $.get(url_returnable_sale_items, {
                    sale_id: sale_id
                })
                .then(function(data) {

                    $('.returnable-sale-items').html(data)
                })
            }
        })
    </script>

@endsection
