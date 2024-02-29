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
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">

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
                            <a href="{{ route('contra.index') }}" class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
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
            <form action="{{route('vouchers.update', $voucher->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="voucher_type" value="Contra">
                <div class="row">
                    <div class="col-sm-12 px-4">

                        <div class="row">
                            <div class="col-md-9" style="padding-left: 0px;">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-4 col-sm-12">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    Reference
                                                </span>
                                                <input name="reference" value="{{ old('reference') ?: $voucher->reference }}" class="form-control" type="text">
                                                @error('reference')
                                                <span class="text-danger"> {{ $message }}</span>
                                                @enderror
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
                                                <input name="date" class="form-control date-picker" id="id-date-picker-1" type="text" value="{{ old('date') ?: $voucher->date }}" data-date-format="yyyy-mm-dd">
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
                                            <td>Account Name</td>
                                            <td class="text-right" width="150px;">Debit</td>
                                            <td class="text-right" width="150px;">Credit</td>
                                            <td width="50px;"></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($voucher->details as $detail)
                                        <tr>
                                            <td class="count"></td>
                                            <input type="hidden" name="detail_ids[]" value="{{ $detail->id }}">
                                            <td>
                                                <div class="col-sm-12">
                                                    <select name="account_ids[]" value="{{ old('account_ids') }}" id="account_id" class="chosen-select-100-percent" data-placeholder="- Select Account -">
                                                        <option value=""></option>
                                                        @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}" {{ $account->id == $detail->account_id ? 'selected':'' }} data-balance="{{ $account->balance ?? 0 }}" {{ oldSelect('account_id', $account->id) }}>{{ $account->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('account_ids')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </td>
                                            <td>
                                            @if($detail->balance_type == 'Debit')
                                                <input name="debit[]" value="{{ old('debit') ?: $detail->amount }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control text-right input-debit calculate-total input-sm" />
                                                @error('debit')
                                                <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            @else
                                            <input name="debit[]" value="{{ old('debit') ?: '0' }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control text-right input-debit calculate-total input-sm" />
                                            @endif
                                            </td>
                                            <td>
                                            @if($detail->balance_type == 'Credit')
                                                <input name="credit[]" value="{{ old('credit') ?: $detail->amount }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control text-right input-credit calculate-total input-sm" />
                                                @error('credit')
                                                <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            @else
                                                <input name="credit[]" value="{{ old('credit') ?: '0' }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control text-right input-credit calculate-total input-sm" />
                                            @endif
                                            </td>
                                            <td class="text-center"><a class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td class="text-right item-serial">Total</td>
                                            <td>
                                                <input name="total_debit" readonly disabled class="total-debit text-right form-control" style="border: none;">
                                            </td>
                                            <td>
                                                <input name="total_credit" readonly disabled class="total-credit text-right form-control" style="border: none;">
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
                                <input type="text" required class="form-control" name="description" value="{{ old('description') ?: $voucher->description }}" placeholder="Narration / Description">

                                @error('description')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-xs-12 col-sm-7" style="padding-left: 0px;">
                                <label class="ace-file-input ace-file-multiple">
                                    <input type="file" name="attachment" id="footer">
                                    <a class="remove" href="#"><i class=" ace-icon fa fa-times"></i></a>
                                </label>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-12">
                                        @if($voucher->attachment)
                                        <label class="ace-file-input ace-file-multiple">
                                            <a href="{{ asset('http://127.0.0.1:8000/' .$voucher->attachment) }}" target="_blank">
                                            <img src="{{ asset($voucher->attachment) }}" style="width: 100px" alt="It's A File..."><br>
                                            <span>Click Here To Preview</span>
                                        </a>
                                        </label>
                                        @else
                                        <label>No file uploaded!<label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-right: 0px;">
                                <div class="form-group">
                                    <div class="pull-right mt-5">
                                        <button type="submit" class="btn btn-sm btn-success save-btn">
                                            <i class="fa fa fa-save"></i>
                                            Update
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
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
                                <select id="account_id" name="account_ids[]" value="{{ old('account_ids') }}" class="chosen-select-100-percent" data-placeholder="- Select Account -">
                                    <option value=""></option>
                                    @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" data-balance="{{ $account->balance ?? 0 }}" {{ oldSelect('account_id', $account->id) }}>{{ $account->name }}</option>
                                    @endforeach
                                </select>
                                @error('account_ids')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                        <td>
                            <input name="debit[]" value="{{ old('debit') }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-credit', this)" class="form-control text-right calculate-total input-debit input-sm" />
                            @error('debit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </td>
                        <td>
                            <input name="credit[]" value="{{ old('credit') }}" type="text" onkeypress="return onlyNumber(event)" onclick="enableMe(this)" onkeyup="disabledReverse('input-debit', this)" class="form-control text-right input-credit calculate-total input-sm" />
                            @error('credit')
                                <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </td>
                        <td><a class="ibtnDel btn btn-sm btn-danger"><i class="fa fa-trash"></i></a></td>
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

    $(document).ready(function() {
        calculateAmount()
        saveButton.attr('disabled', false)
    });

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
        }else{
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