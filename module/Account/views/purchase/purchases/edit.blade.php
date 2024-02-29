@extends('layouts.master')


@section('title', 'Edit Purchase')



@section('page-header')
<i class="fa fa-edit"></i> Edit Purchase
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
            /* Set a counter named 'section', and its initial value is 0. */
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
                        <a href="{{ route('acc-purchases.index') }}" ><i class="fa fa-list-alt"></i> List</a>
                    </div>


                    <div class="widget-toolbar">
                        <a href="{{ route('acc-purchases.create') }}" ><i class="fa fa-plus"></i> Create</a>
                    </div>

                </div>





                <!-- body -->
                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('acc-purchases.update', $purchase->id) }}" method="post" enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            @include('partials._alert_message')

                            <input hidden name="account_id" value="{{ $account->id }}">


                            <div class="row">




                                <!-- Supplier -->
                                <div class="col-sm-5 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Supplier<span class="text-danger">*</span>
                                        </span>

                                        <select required name="supplier_id" id="form_field" class="chosen-select-100-percent" data-placeholder="- Select Supplier -">
                                            <option></option>

                                            @foreach($suppliers as $id => $name)
                                                <option value="{{ $id }}" {{ old('supplier_id', $purchase->supplier_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>





                                <!-- Companies -->
                                <div class="col-sm-5 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">Company Name</span>

                                        <select required name="company_id" id="account_id" class="chosen-select-100-percent" data-placeholder="- Select Company -">
                                            <option></option>

                                            @foreach($companies as $id => $name)

                                                @if(count($companies) > 1)
                                                    <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                                @else
                                                    <option value="{{ $id }}" selected>{{ $name }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('date')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
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








                                <!-- Item Details Table -->
                                <div class="col-sm-12 mt-3">
                                    <table id="myTable" class="table table-bordered order-list">



                                        <!-- head -->
                                        <thead>
                                            <tr>
                                                <td width="40px;">SL.</td>
                                                <td>Product Name<span class="text-danger">*</span></td>
                                                <td class="text-right" width="120px;">Price</td>
                                                <td class="text-right" width="120px;">Quantity</td>
                                                <td width="120px;">Subtotal</td>
                                                <td width="50px;"></td>
                                            </tr>
                                        </thead>



                                        <!-- body -->
                                        <tbody>
                                            @if (old('product_id'))
                                                @foreach(old('product_id') as $key => $value)
                                                    <tr>
                                                        <td class="count"></td>
                                                        <td>
                                                            <div class="col-sm-12 prod-price">
                                                                <select required name="product_id[]" onchange="enableQty('purchasePrice-input', 'quantity-enable', this)" class="input-qty chosen-select-100-percent" data-placeholder="- Select Product -">
                                                                    <option></option>
                                                                    @foreach($products as $prod)
                                                                    <option value="{{ $prod->id }}" data-price="{{ $prod->purchase_price }}" {{ old('product_id')[$key] == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="detail_ids[]" value="{{ old('detail_ids')[$key] }}">
                                                            <input name="purchase_price[]" type="text" value="{{ old('purchase_price')[$key] }}" class="form-control text-right purchasePrice-input only-number" />
                                                            @error('debit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="quantity[]" required type="text" value="{{ old('quantity')[$key] }}" class="form-control text-right only-number quantity calculate-total quantity-enable" />
                                                            @error('credit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="subtotal[]" readonly type="text" value="{{ old('subtotal')[$key] }}" class="form-control only-number text-right sub-total input-sm" />
                                                            @error('credit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td class="text-center"><a class="btn btn-sm btn-danger" disabled="disabled"><i class="fa fa-trash"></i></a></td>
                                                    </tr>
                                                @endforeach

                                            @else

                                                @foreach($purchase->details as $detail)
                                                    <tr>
                                                        <td class="count"></td>
                                                        <td>
                                                            <div class="col-sm-12 prod-price">
                                                                <select required name="product_id[]" onchange="enableQty('purchasePrice-input', 'quantity-enable', this)" class="input-qty chosen-select-100-percent" data-placeholder="- Select Product -">
                                                                    <option></option>
                                                                    @foreach($products as $prod)
                                                                    <option value="{{ $prod->id }}" {{ $detail->product_id == $prod->id ? 'selected' : '' }} data-price="{{ $prod->purchase_price }}">{{ $prod->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="detail_ids[]" value="{{ $detail->id }}">
                                                            <input name="purchase_price[]" type="text" value="{{ $detail->price }}" class="form-control only-number text-right purchasePrice-input" />
                                                            @error('debit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="quantity[]" required type="text" value="{{ $detail->quantity }}" class="form-control text-right only-number quantity calculate-total quantity-enable" />
                                                            @error('credit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="subtotal[]" readonly type="text" value="{{ $detail->amount }}" class="form-control only-number text-right sub-total input-sm" />
                                                            @error('credit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td class="text-center"><a class="btn btn-sm btn-danger" disabled="disabled"><i class="fa fa-trash"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>




                                        <!-- footer -->
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-right item-serial">Total</td>
                                                <td>
                                                    <input readonly name="qty_total" value="{{ old('qty_total', $purchase->qty_total) }}" class="quantityTotal text-right form-control">
                                                </td>
                                                <td>
                                                    <input readonly name="qty_amount" value="{{ old('qty_amount', $purchase->qty_amount) }}" class="itemTotal text-right form-control">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" id="addrow">+</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    







                                    <!-- Discount Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Discount Amount</label>
                                                <div class="col-xs-12 col-sm-8 @error('cost') has-error @enderror">
                                                    <input name="discount_amount" value="{{ old('discount_amount', $purchase->discount_amount) }}" value="0.00" disabled class="discount only-number calculate-total dicount-enable text-right form-control">
                                                    @error('cost')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Total Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Total Amount</label>
                                                <div class="col-xs-12 col-sm-8 @error('end_date') has-error @enderror">
                                                    <input name="total_amount" value="{{ old('total_amount', $purchase->total_amount) }}" readonly class="totalAmount text-right form-control">
                                                    @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Paid Amount -->
                                    <div class="row">
                                        <div class="col-md-5 pull-right">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Paid Amount</label>
                                                <div class="col-xs-12 col-sm-8 @error('end_date') has-error @enderror">
                                                    <input name="paid_amount" value="{{ old('paid_amount', $purchase->paid_amount) }}" autocomplete="off" class="paidAmount only-number calculate-paid total-credit text-right form-control">
                                                    @error('end_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
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
                                                    <input name="due_amount" value="{{ old('due_amount', $purchase->due_amount) }}" readonly class="dueAmount total-credit text-right form-control">
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
                                            <button type="submit" class="btn btn-sm btn-info save-btn">
                                                <i class="fa fa fa-edit"></i>
                                                Update
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
        const inputDebit            = $('.input-debit')
        const inputCredit           = $('.input-credit')
        const draftValue            = $('.draft-value')

        const rowItem = `<tr>
                            <td class="count"></td>
                            <td>
                                <div class="col-sm-12 prod-price">
                                    <select required name="product_id[]" onchange="enableQty('purchasePrice-input', 'quantity-enable', this)" class="input-qty chosen-select-100-percent" data-placeholder="- Select Product -">
                                        <option></option>
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->id }}" data-price="{{ $prod->purchase_price }}">{{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_ids')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="detail_ids[]" value="">
                                <input name="purchase_price[]" type="text" class="form-control only-number text-right purchasePrice-input" />
                                @error('debit')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input name="quantity[]" disabled required type="text" class="form-control only-number text-right quantity calculate-total quantity-enable" />
                                @error('credit')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input name="subtotal[]" readonly type="text" class="form-control only-number text-right sub-total input-sm" />
                                @error('credit')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </td>
                            <td class="text-center"><a class="btn btn-sm btn-danger ibtnDel"><i class="fa fa-trash"></i></a></td>
                        </tr>`



        
        
        $('select').chosen({
            allow_single_deselect: true
        });



        function enableQty($class_name, $qty, object) 
        {
            let getPrice    = $(object).find('option:selected').data('price')
            let showPrice   = $(object).closest('tr').find('.' + $class_name)
            let qty         = $(object).closest('tr').find('.' + $qty)

            showPrice.val(getPrice)
            qty.attr('disabled', false)
        }




        $(document).on("keyup", ".calculate-total", function() {

            calculateRowMultiply()

            calculateAmount()

            calculateDiscount()

            calculateDue()

            enableDiscountField.attr('disabled', false)
        })




        $(document).on("keyup", ".calculate-paid", function() {
            calculateDue()
        })

        function calculateDue() 
        {
            let totalAmount     = Number($(".totalAmount").val())
            let paidAmount      = Number($(".paidAmount").val())
            let dueAmount       = totalAmount - paidAmount

            $(".dueAmount").val(dueAmount)
        }

        function calculateRowMultiply() 
        {
            $('table tr:has(td):not(:last)').each(function() {

                let count   = 0
                let qty     = $(this).find('.quantity').val()
                let prc     = $(this).find('.purchasePrice-input').val()

                $('.quantity').each(function() {
                    count = qty * prc
                })

                $(this).find('.sub-total').val(count)

            });
        }





        function calculateAmount() 
        {
            var totalAmount = 0;
            var discount = $('.discount').val();

            // Product purchase Single Price
            var purchase_price = 0;
            $(".purchasePrice-input").each(function() {
                purchase_price += Number($(this).val());
            });

            // Sum all quantity
            var quantity = 0;
            $(".quantity").each(function() {
                quantity += Number($(this).val());
            });

            // Sum all Sub-total
            var totalAmount = 0;
            $(".sub-total").each(function() {
                totalAmount += Number($(this).val());
            });

            $(".purchasePrice-total").val(purchase_price)
            $(".quantityTotal").val(quantity)
            $(".itemTotal").val(totalAmount)
        }






        function calculateDiscount() 
        {
            let itemTotal       = $(".itemTotal").val()
            let discount        = $(".discount").val()
            let totalAmount     = Number(itemTotal) - Number(discount)


            $(".totalAmount").val(totalAmount)
        }




        $(document).ready(function() {

            let i = 0

            $("#addrow").on("click", function() {
                $("table.order-list").append(rowItem)
                chosenSelectInit()
                i++
            });




            $("table.order-list").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
                i -= 1
                calculateAmount()
                calculateDiscount()
                calculateDue()
            });
        });
    </script>

@endsection