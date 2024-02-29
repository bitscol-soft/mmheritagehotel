@extends('layout.app')
@section('title', 'Hospital Services Sales')
@push('style')
    <style>
        input.form-control.small-box {
            height: 27px;
        }

        form .form-group .control-label {
            padding-top: 7px;
        }

    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-heading-btn pull-right">
                        @if (hasPermission('service.view', $slugs))
                            <a class="btn btn-success btn-sm" href="{{ route('hospital-services-sales.index') }}">
                                <i class="fa fa-bars"></i> All Sales</a>
                        @endif
                    </div>
                    <h4 class="panel-title">Service Sales</h4>
                </div>
                <div class="panel-body">
                    <form name="update-form" method="POST"
                        action="{{ route('hospital-services-sales.update', $hospitalServiceSale) }}"
                        accept-charset="UTF-8" class="form-horizontal author_form" id="commentForm" role="form"
                        data-parsley-validate novalidate enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="col-md-12">
                            <div class="form-group col-md-6">
                                <label class=" col-md-3 control-label" for="Date">Date * :</label>
                                <div class="col-md-8">
                                    <input class="form-control" readonly type="text" name="invoice_date"
                                        value="{{ $hospitalServiceSale->invoice_date }}" placeholder="Enter date"
                                        tabindex="-1" /><br>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class=" col-md-3 control-label" for="Date">Invoice ID</label>
                                <div class="col-md-8">
                                    <input class="form-control" autocomplete="off" type="text" name="invoice_no"
                                        value="{{ $hospitalServiceSale->invoice_no }}" placeholder="Invoice ID"
                                        tabindex="-1" readonly /><br>
                                </div>
                            </div>
                            <div class="border-bottom">
                                <hr>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="patients_name">Patients Name * :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="patient_name" name="patient_name"
                                        value="{{ $hospitalServiceSale->patient->name }}" placeholder="Patients Name"
                                        readonly required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="customer_id_no">Patient Id * :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text"
                                        value="{{ $hospitalServiceSale->patient->customer_id }}" id="customer_id_no"
                                        name="customer_id_no" placeholder="Patient id here" tabindex="-1" required
                                        readonly />
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="patients_age">Age * :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="number" min="0" step="any"
                                        value="{{ $hospitalServiceSale->patient->age }}" id="patient_age" name="age"
                                        placeholder="Age in year" readonly required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="sex">Sex * :</label>
                                <div class="col-md-9">
                                    <label><input id="male" type="radio" name="sex" value="1" disabled>
                                        Male</label>&nbsp;&nbsp;&nbsp;
                                    <label><input id="female" type="radio" name="sex" value="2" disabled>
                                        Female</label>&nbsp;&nbsp;&nbsp;
                                    <label><input id="other" type="radio" name="sex" value="3" disabled> Other</label>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="mobile_no">Mobile Number :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="number" min="0" id="mobile_no"
                                        value="{{ $hospitalServiceSale->patient->mobile_number }}" name="mobile_number"
                                        placeholder="Mobile Number" readonly required />
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="patients_disease">Patients History :</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="disease" name="disease"
                                        placeholder="Patients diseases" rows="2"
                                        readonly>{{ $hospitalServiceSale->patient->disease }}</textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="referred_doctor">Referred Doctor * :</label>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="referred_doctor_name"
                                        name="referred_doctor_name"
                                        value="{{ $hospitalServiceSale->referredDoctor->name }}"
                                        placeholder="Referred Doctor" />
                                    <input type="hidden" id="referred_doctor_id"
                                        value="{{ $hospitalServiceSale->referred_doctor_id }}"
                                        name="referred_doctor_id" />
                                </div>
                            </div>
                            <div class="form-group col-md-6 ">
                                <label class="col-md-3 control-label" for="delivery-date">Delivery Date * :</label>
                                <div class="col-md-8">
                                    <input id="delivery-date" class="form-control invoice_date" autocomplete="off"
                                        type="text" name="delivery_date"
                                        value="{{ $hospitalServiceSale->delivery_date }}" placeholder=" Enter date"
                                        tabindex="-1" /><br>
                                </div>
                            </div>
                            {{-- </div> --}}

                            <!-- transition -->
                            <div class="view_center_folwchart">
                                <div class='row'>
                                    <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                        <table class="table table-bordered table-hover" id="table_auto">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th width="12%" class="text-right">Price</th>
                                                    <th width="12%" class="text-right">Discount</th>
                                                    <th width="12%" class="text-right">Total Price</th>
                                                    {{-- <th width="7%">Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="container">
                                                @foreach ($hospitalServiceSale->items as $key => $item)
                                                    <tr class="repeat-group">
                                                        <td>
                                                            <input type="hidden" class="service-ids small-box"
                                                                value="{{ $item->service_id }}" name="service_id[]"
                                                                data-pattern-name="service[++][service_id]">
                                                            <input class="form-control service-names small-box"
                                                                value="{{ $item->service->name }}" type="text"
                                                                name="name[]" id="service_0_name"
                                                                data-pattern-name="service[++][name]"
                                                                data-pattern-id="service_++_name" required readonly />
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-right service-prices small-box"
                                                                value="{{ $item->service->price }}" type="text"
                                                                tabindex="-1" name="price[]" id="service_0_price"
                                                                data-pattern-name="service[++][price]"
                                                                data-pattern-id="service_++_price" readonly />
                                                        </td>
                                                        {{-- <td> --}}
                                                        {{-- <input class="form-control text-right service-discounts small-box" value="{{$item->discount}}" type="text" tabindex="-1" name="service[0][discount]" id="service_0_discount" data-pattern-name="service[++][discount]" data-pattern-id="service_++_price" readonly/> --}}
                                                        {{-- </td> --}}
                                                        <td>
                                                            <input
                                                                class="form-control text-right service-total-prices small-box"
                                                                value="{{ $item->amount }}" type="text" tabindex="-1"
                                                                name="service[0][total_price]" id="service_0_total_price"
                                                                data-pattern-name="service[++][total_price]"
                                                                data-pattern-id="service_++_total_price" readonly />
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br>

                                <div class='row'>
                                    <div class='col-xs-12 col-sm-7 col-md-7 col-lg-7'>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Referance By:</label>
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" id="referance_name"
                                                    value="{{ optional($hospitalServiceSale->commissionAgent)->name }}"
                                                    name="reference_name" placeholder="Doctor Name" />
                                                <input type="hidden" id="commission_refer_id"
                                                    value="{{ optional($hospitalServiceSale->commissionAgent)->id }}"
                                                    name="commission_refer_id" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class='col-xs-12 col-sm-5 col-md-5 col-lg-5'>
                                        <div class="form-inlines">
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Sub Total:</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input value="{{ $hospitalServiceSale->subtotal }}" type="number"
                                                        min="0" step="any" class="form-control" name="subtotal"
                                                        id="subTotal" placeholder="Subtotal" tabindex="-1" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Discount:</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input onkeyup="discountAmount()"
                                                        value="{{ $hospitalServiceSale->discount }}" type="number"
                                                        min="0" step="any" class="form-control" name="discount"
                                                        id="discount" placeholder="Discount" ondrop="return false;"
                                                        onpaste="return false;" required>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Total Amount:</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input
                                                        value="{{ $hospitalServiceSale->subtotal - $hospitalServiceSale->discount }}"
                                                        type="number" min="0" step="any" class="form-control"
                                                        name="payable_amount" id="payable_amount"
                                                        placeholder="Payable Amount" tabindex="-1" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Advance Paid :</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input type="number" value="{{ $hospitalServiceSale->paid }}" min="0"
                                                        step="any" class="form-control" id="paid_amount"
                                                        placeholder="Paid Amount" ondrop="return false;"
                                                        onpaste="return false;" readonly required>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Amount Due :</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input value="{{ $hospitalServiceSale->due }}" type="number" min="0"
                                                        step="any" class="form-control amountDue" name="due_amount"
                                                        id="due_amount" placeholder="Amount Due" tabindex="-1"
                                                        ondrop="return false;" onpaste="return false;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <input type="submit" class="btn btn-success col-md-10 pull-right"
                                                        name="draft" value="Draft">
                                                </div>
                                                <div class="col-md-8 col-sm-8 no-padding">
                                                    <input type="submit" class="btn btn-primary col-md-12" name="confirm"
                                                        value="Confirm">
                                                </div>
                                                <label class="control-label col-md-2 col-sm-2"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.form-repeater.js') }}"></script>
    <script type="text/javascript">
        $('.invoice_date').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            changeMonth: true,
            changeYear: true,
        });

        $('#delivery-date').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            changeMonth: true,
            changeYear: true,
        });
        // $(".invoice_date").datepicker("setDate", new Date());

        function totalAmount() {
            var total = 0;
            $('.service-prices').each(function(i, price) {
                var p = $(price).val();
                total += p ? parseFloat(p) : 0;
            });
            var subtotal = $('#subTotal').val(total);
            discountAmount();
        }

        function discountAmount() {
            var payableAmount = 0;
            $('#discount').on('keyup blur change', function() {
                payableAmount = parseFloat($('#subTotal').val()) - (parseFloat($(this).val()) ? parseFloat($(this)
                    .val()) : 0);
                $('#payable_amount').val(payableAmount);
                dueAmount(payableAmount);
            }).trigger('change');
        }

        var payableAmount = 0;
        $('#discount').on('keyup blur change', function() {
            payableAmount = parseFloat($('#subTotal').val()) - (parseFloat($(this).val()) ? parseFloat($(this)
                .val()) : 0);
            $('#payable_amount').val(payableAmount);
            dueAmount(payableAmount);
        }).trigger('change');

        function dueAmount(amount) {
            $('#paid_amount').on('change blur keyup', function() {
                $('#due_amount').val(parseFloat(amount) - parseFloat($(this).val()));
            }).trigger('change');
        }
    </script>

    <script>
        {{-- $('.container').repeater({ --}}
        {{-- btnAddClass: 'r-btnAdd', --}}
        {{-- btnRemoveClass: 'r-btnRemove', --}}
        {{-- groupClass: 'repeat-group', --}}
        {{-- afterDelete: function () { --}}
        {{-- totalAmount(); --}}
        {{-- } --}}
        {{-- }, [ --}}
        {{-- @foreach ($hospitalServiceSale->items as $key => $item) --}}
        {{-- { --}}
        {{-- 'service[{{$key}}][service_id]': '{{$item->id}}', --}}
        {{-- 'service[{{$key}}][name]': '{{$item->service->name}}', --}}
        {{-- 'service[{{$key}}][price]': '{{$item->service->price}}', --}}
        {{-- 'service[{{$key}}][discount]': '{{$item->discount}}', --}}
        {{-- 'service[{{$key}}][total_price]': '{{$item->total_price}}', --}}
        {{-- }, --}}
        {{-- @endforeach --}}
        {{-- ]); --}}
    </script>

@endsection

@push('js-script')
    <script>
        {{-- document.forms['update-form'].elements['sex'].value={{$hospitalServiceSale->patient->sex}} --}}
    </script>
@endpush
