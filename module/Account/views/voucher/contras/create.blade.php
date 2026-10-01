@extends('layouts.master')

@section('title', 'Contra Voucher')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Create Contra Voucher
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

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ route('voucher-contras.index') }}"
                                    class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip"
                                    tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-list bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space"></div>





                <!-- INPUTS -->
                <form id="form" action="{{ route('voucher-contras.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="voucher_type" value="Contra">
                    <div class="row">
                        <div class="col-sm-12 px-4">

                            <div class="row">
                                <div class="col-md-9" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon input-sm">
                                                        Company Name
                                                    </span>
                                                    <select required name="company_id" class="chosen-select-100-percent"
                                                        data-placeholder="- Select Account -">
                                                        <option></option>


                                                        @foreach ($company as $key => $name)
                                                            <option value="{{ $key }}"
                                                                {{ auth()->user()->company->id == $key ? 'selected' : '' }}>
                                                                {{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('account_ids')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9" style="padding-left: 0px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-12">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        Reference
                                                    </span>
                                                    <input name="reference" value="{{ old('reference') }}"
                                                        class="form-control" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3" style="padding-right: 0px;">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-4 col-sm-12">
                                                <div class="input-group">
                                                    <input name="date" class="form-control date-picker text-center"
                                                        id="id-date-picker-1" type="text"
                                                        value="{{ old('date') ?: date('Y-m-d') }}"
                                                        data-date-format="yyyy-mm-dd">
                                                    <span class="input-group-addon">
                                                        Date
                                                    </span>
                                                    @error('date')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>
                                            <tr>
                                                <td width="40px;">SL.</td>
                                                <td>Account Name<span class="text-danger">*</span></td>
                                                <td class="text-right" width="150px;">Debit</td>
                                                <td class="text-right" width="150px;">Credit</td>
                                                <td width="50px;"></td>
                                            </tr>
                                        </thead>




                                        <tbody>
                                            @if (old('account_ids'))
                                                @foreach (old('account_ids') as $key => $value)
                                                    <tr>
                                                        <td class="count"></td>
                                                        <td>
                                                            <div class="col-sm-12">
                                                                <select required="required" name="account_ids[]"
                                                                    id="account_id" class="chosen-select-100-percent"
                                                                    data-placeholder="- Select Account -">
                                                                    <option></option>
                                                                    @foreach ($accounts as $account)
                                                                        <option value="{{ $account->id }}"
                                                                            {{ old('account_ids')[$key] == $account->id ? 'selected' : '' }}>
                                                                            {{ $account->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('account_ids')
                                                                    <span class="text-danger"> {{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input name="debit[]" value="{{ old('debit')[$key] }}"
                                                                type="text" onkeypress="return checkOnlyNumber(event)"
                                                                onclick="enableMe(this)"
                                                                onkeyup="disabledReverse('input-credit', this)"
                                                                class="form-control text-right input-debit calculate-total input-sm" />
                                                            @error('debit')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input name="credit[]" value="{{ old('credit')[$key] }}"
                                                                type="text" onkeypress="return checkOnlyNumber(event)"
                                                                onclick="enableMe(this)"
                                                                onkeyup="disabledReverse('input-debit', this)"
                                                                class="form-control text-right input-credit calculate-total input-sm" />
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
                                                    <td class="count"></td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <select required="required" name="account_ids[]" id="account_id"
                                                                class="chosen-select-100-percent"
                                                                data-placeholder="- Select Account -">
                                                                <option></option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        {{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('account_ids')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input name="debit[]" type="text"
                                                            onkeypress="return checkOnlyNumber(event)"
                                                            onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-credit', this)"
                                                            class="form-control text-right input-debit calculate-total input-sm" />
                                                        @error('debit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input name="credit[]" type="text"
                                                            onkeypress="return checkOnlyNumber(event)"
                                                            onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-debit', this)"
                                                            class="form-control text-right input-credit calculate-total input-sm" />
                                                        @error('credit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center"><a class="btn btn-sm btn-danger"
                                                            disabled="disabled"><i class="fa fa-trash-o"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="count"></td>
                                                    <td>
                                                        <div class="col-sm-12">
                                                            <select required id="account_id" name="account_ids[]"
                                                                class="chosen-select-100-percent"
                                                                data-placeholder="- Select Account -">
                                                                <option value=""></option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}"
                                                                        data-balance="{{ $account->balance ?? 0 }}">
                                                                        {{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('account_ids')
                                                                <span class="text-danger"> {{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input name="debit[]" type="text"
                                                            onkeypress="return checkOnlyNumber(event)"
                                                            onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-credit', this)"
                                                            class="form-control text-right calculate-total input-debit input-sm" />
                                                        @error('debit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input name="credit[]" type="text"
                                                            onkeypress="return checkOnlyNumber(event)"
                                                            onclick="enableMe(this)"
                                                            onkeyup="disabledReverse('input-debit', this)"
                                                            class="form-control text-right input-credit calculate-total input-sm" />
                                                        @error('credit')
                                                            <span class="text-danger"> {{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td class="text-center"><a class="btn btn-sm btn-danger"
                                                            disabled="disabled"><i class="fa fa-trash-o"></i></a></td>
                                                </tr>
                                            @endif
                                        </tbody>



                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td class="text-right item-serial">Total</td>
                                                <td>
                                                    <input name="total_debit" readonly disabled
                                                        class="total-debit text-right form-control" style="border: none;">
                                                </td>
                                                <td>
                                                    <input name="total_credit" readonly disabled
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

                                <div class="pull-right mt-5">
                                    <button type="button" id="draft" class="btn btn-sm btn-primary save-btn" disabled>
                                        Draft
                                        <i class="fa fa-file"></i>
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
                                    <select id="account_id" name="account_ids[]" class="chosen-select-100-percent" data-placeholder="- Select Account -">
                                        <option value=""></option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input name="debit[]" type="text" onkeypress="return checkOnlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control text-right calculate-total input-debit input-sm" />
                            </td>
                            <td>
                                <input name="credit[]" type="text" onkeypress="return checkOnlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control text-right input-credit calculate-total input-sm" />
                            </td>
                            <td><a class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></a></td>
                        </tr>`

        jQuery(function($) {
            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });

            // Start header, footer
            $('#header').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit
            });
            $('#footer').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit
            });
            // End header, footer
        });

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

        function checkOnlyNumber(evnt) {
            let keyCode = evnt.charCode

            let str = evnt.target.value
            let n = str.includes(".")

            if (keyCode == 13) {
                evnt.preventDefault();
            }

            if (keyCode == 46 && n) {
                return false
            }

            if (str.length > 12) {
                showAlertMessage('Number length out of range', 3000)
                calculateAmount()
                return false
            }
            return (keyCode >= 48 && keyCode <= 57) || keyCode == 13 || keyCode == 46
        }

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
    </script>


    <script type="text/javascript">
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
