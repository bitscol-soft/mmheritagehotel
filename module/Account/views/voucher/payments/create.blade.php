@extends('layouts.master')


@section('title', 'Payment Voucher')


@section('page-header')
    <i class="fa fa-plus-circle"></i> Payment Voucher
@stop


@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />


    <style>
        td {
            padding-bottom: 3px !important;
            padding-top: 3px !important;
        }

        table {
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
        <div class="col-sm-12">

            @include('partials._alert_message')



            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>


                    <div class="widget-toolbar">
                        <a href="{{ route('voucher-payments.index') }}"><i class="fa fa-list-alt"></i> List</a>
                    </div>

                </div>








                <!-- Form  -->
                <form id="form" action="{{ route('voucher-payments.store') }}" method="post" enctype="multipart/form-data">
                    @csrf


                    <input type="hidden" name="voucher_type" value="Payment">


                    <div class="row mt-1">

                        <div class="col-sm-12 px-3">


                            <!-- Filter -->
                            <div class="row">

                                <!-- Company -->
                                <div class="col-sm-5 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">Company <strong
                                                class="text-danger">*</strong></span>

                                        <select required name="company_id" id="account_id" class="chosen-select-100-percent"
                                            data-placeholder="- Select Company -">
                                            <option></option>

                                            @foreach ($companies as $id => $name)

                                                @if (count($companies) > 1)
                                                    <option value="{{ $id }}"
                                                        {{ old('company_id') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @else
                                                    <option value="{{ $id }}" selected>{{ $name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>

                                        @error('date')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>




                                <!-- Reference -->
                                <div class="col-sm-5 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Reference
                                        </span>
                                        <input name="reference" value="{{ old('reference') }}" class="form-control"
                                            type="text">
                                    </div>
                                </div>



                                <!-- Date -->
                                <div class="col-sm-2 my-1">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            Date
                                        </span>
                                        <input name="date" class="form-control date-picker text-center" type="text"
                                            value="{{ old('date', date('Y-m-d')) }}" data-date-format="yyyy-mm-dd">

                                        @error('date')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>








                            <!-- Item Detail -->
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <table id="myTable" class="table table-bordered order-list">

                                        <!-- Table Header -->
                                        <thead>
                                            <tr>
                                                <td width="50px;" class="text-center">SL.</td>
                                                <td>Account<span class="text-danger">*</span></td>
                                                <td class="text-right" width="150px;">Debit</td>
                                                <td class="text-right" width="150px;">Credit</td>
                                                <td width="50px;"></td>
                                            </tr>
                                        </thead>





                                        <!-- Item Detail Table Body -->
                                        <tbody>
                                            @if (old('account_ids'))
                                                @foreach (old('account_ids') as $key => $value)
                                                    <tr>
                                                        <td class="count text-center"></td>
                                                        <td>
                                                            <div class="col-sm-12">
                                                                <select required="required" name="account_ids[]"
                                                                    id="account_id" class="chosen-select-100-percent"
                                                                    data-placeholder="- Select Account -">
                                                                    <option></option>
                                                                    @foreach ($accounts as $id => $value)
                                                                        <option value="{{ $value->id }}">
                                                                            {{ $value->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('account_ids')
                                                                    <span class="text-danger"> {{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input name="debit[]" value="{{ old('debit')[$key] }}"
                                                                type="text" onclick="enableMe(this)"
                                                                onkeyup="disabledReverse('input-credit', this)"
                                                                class="form-control only-number text-right input-debit calculate-total input-sm" />
                                                            @error('debit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="credit[]" value="{{ old('credit')[$key] }}"
                                                                type="text" onclick="enableMe(this)"
                                                                onkeyup="disabledReverse('input-debit', this)"
                                                                class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                            @error('credit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td class="text-center"><a class="btn btn-sm btn-danger"
                                                                disabled="disabled"><i class="fa fa-trash-o"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="count text-center"></td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <select required="required" name="account_ids[]"
                                                                id="account_ids" class="chosen-select-100-percent"
                                                                data-placeholder="- Select Account -">
                                                                <option></option>

                                                                @foreach ($accounts as $id => $value)
                                                                    <option value="{{ $value->id }}">
                                                                        {{ $value->name }}</option>
                                                                @endforeach
                                                            </select>


                                                            @error('account_ids')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input name="debit[]" type="text" onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-credit', this)"
                                                            class="form-control only-number text-right input-debit calculate-total input-sm" />
                                                        @error('debit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input name="credit[]" type="text" onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-debit', this)"
                                                            class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                        @error('credit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center"><a class="btn btn-sm btn-danger"
                                                            disabled="disabled"><i class="fa fa-trash-o"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="count text-center"></td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <select required id="account_ids" name="account_ids[]"
                                                                class="chosen-select-100-percent"
                                                                data-placeholder="- Select Account -">
                                                                <option value=""></option>

                                                                @foreach ($accounts as $id => $value)
                                                                    <option value="{{ $value->id }}">
                                                                        {{ $value->name }}</option>
                                                                @endforeach
                                                            </select>


                                                            @error('account_ids')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input name="debit[]" type="text" onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-credit', this)"
                                                            class="form-control only-number text-right calculate-total input-debit input-sm" />
                                                        @error('debit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input name="credit[]" type="text" onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-debit', this)"
                                                            class="form-control only-number text-right input-credit calculate-total input-sm" />
                                                        @error('credit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center"><a class="btn btn-sm btn-danger"
                                                            disabled="disabled"><i class="fa fa-trash-o"></i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>



                                        <!-- Table Footer -->
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td class="text-right item-serial">Total</td>
                                                <td>
                                                    <input name="total_debit" readonly value="{{ old('total_debit') }}"
                                                        disabled class="total-debit text-right form-control"
                                                        style="border: none;">
                                                </td>
                                                <td>
                                                    <input name="total_credit" readonly disabled
                                                        value="{{ old('total_credit') }}"
                                                        class="total-credit text-right form-control" style="border: none;">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success" id="addrow">
                                                        +
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>











                        <div class="col-sm-12 px-4 mt-2 mb-2">

                            <div class="row">
                                <div class="input-group " style="width: 100%!important; float: left; ">
                                    <label class="input-group-addon">Narration/Description</label>
                                    <input type="text" required class="form-control" name="description"
                                        value="{{ old('description') }}" placeholder="Narration / Description">

                                    @error('description')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-group input-group-sm"
                                    style="width: 80%!important; height: 40%!important; float: left; margin-top: 10px;">
                                    <div class="col-xs-6" style="padding-left: 0px;;">
                                        <label class="ace-file-input ace-file-multiple">
                                            <input type="file" name="attachment" id="id-input-file-3" />
                                    </div>
                                </div>





                                <!-- Action -->
                                <div class="pull-right mt-5">
                                    <div class="btn-group">
                                        <button type="button" id="draft" class="btn btn-sm btn-primary save-btn" disabled>
                                            <i class="fa fa-file"></i>
                                            Draft
                                            <input type="hidden" name="draft" class="draft-value" value="0">
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-success save-btn" disabled>
                                            <i class="fa fa fa-save"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

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
        const saveButton = $('.save-btn')
        const inputDebit = $('.input-debit')
        const inputCredit = $('.input-credit')

        const rowItem = `<tr>
                            <td class="count"></td>
                            <td>
                                <div class="col-sm-12">
                                    <select id="account_ids" name="account_ids[]" class="chosen-select-100-percent" data-placeholder="- Select Account -">
                                        <option value=""></option>
                                        @foreach ($accounts as $id => $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('account_ids')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td>
                                <input name="debit[]" type="text" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control only-number text-right calculate-total input-debit input-sm" />
                                @error('debit')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input name="credit[]" type="text" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control only-number text-right input-credit calculate-total input-sm" />
                                @error('credit')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </td>
                            <td><a class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></td>
                        </tr>`




        $('select').chosen({
            allow_single_deselect: true
        });







        $("#draft").click(function() {

            $(".draft-value").val(1);

            $('#form').submit();
        })




        $(document).on("keyup", ".calculate-total", function() {
            calculateAmount()
        });





        function calculateAmount() {

            var debitTotal = 0;

            $(".input-debit").each(function() {
                debitTotal += Number($(this).val());
            });

            var creditTotal = 0;
            $(".input-credit").each(function() {
                creditTotal += Number($(this).val());
            });

            $(".total-debit").val(debitTotal);
            $(".total-credit").val(creditTotal);

            if (debitTotal === creditTotal && debitTotal > 0) {
                saveButton.attr('disabled', false)
            } else {
                saveButton.attr('disabled', true)
            }
        }






        function disabledReverse($class_name, object) {
            let disableItem = $(object).closest('tr').find('.' + $class_name)
            disableItem.attr('readonly', true).val('0')
        }







        function enableMe(object) {
            $(object).attr('readonly', false)
        }






        $(document).ready(function() {
            var i = 0;
            $("#addrow").on("click", function() {
                $("table.order-list").append(rowItem)
                chosenSelectInit()
                i++;
            });

            $("table.order-list").on("click", ".ibtnDel", function(event) {
                $(this).closest("tr").remove();
                i -= 1
                calculateAmount()
            });
        });
    </script>
@endsection
