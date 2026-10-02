@extends('layouts.master')
@section('title', 'Purchase Receive')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop
{{-- {{ $receive_items }} --}}

@section('content')

<x-mm.styles />
<x-mm.page class="mm-gs mm-rst mm-rst-inv mm-rst-form" title="Purchase receive" description="Record the items received against an approved purchase.">
    <x-slot name="actions">
        <a href="{{ route('purchases.index') }}" class="mm-button"><i class="ace-icon fa fa-list-alt"></i> Purchase Receive List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <form class="form-horizontal" action="{{ route('purchase.receives.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger error">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>

                    <ul>
                        @foreach ($errors->all() as $error)
                            @if ($error != 'The company id field is required.')
                                <li>Fillup all items and required quantity</li>
                                @php
                                    break;
                                @endphp
                            @endif

                        @endforeach
                    </ul>
                </div>
            @elseif (session()->get('message'))
                @include('partials._alert_message')
            @endif

            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
            <input type="hidden" name="company_id" value="{{ $purchase->company_id }}">

            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1-1"> Company </label>

                <div class="col-xs-12 col-sm-8">
                    <select class="form-control" disabled="disabled">
                        <option>{{ $purchase->company->name }}</option>
                    </select>
                </div>

            </div>


            <div class="form-group col-">
                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Date</label>
                <div class="col-xs-12 col-sm-8 @error('purchase_date') has-error @enderror">
                    <div class="input-group">
                        <input class="form-control date-picker" name="purchase_receive_date"
                            id="id-date-picker-1" type="text" data-date-format="yyyy-mm-dd" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1-1">
                    {{ $systemSetting->value != null ? $systemSetting->value : 'Reference' }} </label>
                <div class="col-xs-12 col-sm-8">
                    <input type="number" step="0.01" class="form-control"
                        value="{{ $purchase->purchase_reference }}" name="purchase_receive_reference"
                        disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label @error('purchase_challan_number') has-error @enderror"
                    for="form-field-1-1"> Challan No.</label>
                <div class="col-xs-12 col-sm-8 @error('purchase_challan_number') has-error @enderror">
                    <input type="text" step="0.01" class="form-control" name="purchase_challan_number"
                        placeholder="Purchase Challan Number">
                    @error('purchase_challan_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div class="form-group">
                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Add
                    Challan</label>
                <div class="col-xs-12 col-sm-6">
                    <input type="file" name="challan_image" id="id-input-file-3" />
                </div>
            </div>



            <div class="row">
                <div class="col-sm-12">
                    <h3 class="header smaller lighter blue">Purchase Receive</h3>
                    <table id="purchase_receive_table" class="table table-bordered edu1 container">
                        <thead>
                            <tr>
                                <td rowspan="2" width="15%">Item</td>
                                <td rowspan="2">Unit</td>
                                <td rowspan="2">Purchase Qty</td>
                                <td rowspan="2">Received Qty</td>
                                <td rowspan="2">Receiving Qty</td>
                                <td rowspan="2">Rate</td>
                                <td rowspan="2">Total</td>
                                <td rowspan="2" style="width: 120px;">Vendor</td>
                                <td rowspan="2" width="10%">Remarks</td>
                                {{-- <td colspan="2" class="text-center">History</td> --}}
                            </tr>
                            {{-- <tr> --}}
                            {{-- <td width="5%" title="Receive Quantity">Price</td> --}}
                            {{-- <td width="5%">GIN</td> --}}
                            {{-- <td width="5%">Source</td> --}}
                            {{-- </tr> --}}
                        </thead>


                        <tbody>
                            @foreach ($purchase->purchase_details as $key => $details)
                                <input type="hidden" name="purchase_details_id[]"
                                    value="{{ $details->id }}">
                                <tr id="addr0">
                                    <td>
                                        <input type="hidden" name="item_id[]"
                                            value="{{ $details->item_id }}">
                                        <select class="form-control" disabled>
                                            <option>{{ $details->item->name }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $details->item->item_unit->name }}"
                                            name="item_unit_id[]" class="form-control item_unit"
                                            readonly="readonly" />
                                    </td>
                                    <td>
                                        <input type="text" value="{{ $details->quantity }}"
                                            class="form-control purchase_quantity" readonly="readonly">
                                    </td>

                                    <td>
                                        <input type="text" value="{{ $details->received_quantity }}"
                                            name="item_available_quantity[]"
                                            class="form-control already_received" readonly="readonly" />
                                    </td>

                                    <td>
                                        <input type="text" value="{{ old('quantity')[$key] ?? null }}"
                                            name="quantity[]" class="form-control quantity"
                                            autocomplete="off"
                                            onkeypress='return (event.charCode == 46) || (event.charCode >= 48 && event.charCode <= 57)'
                                            onkeyup="sub_total(this)" />
                                    </td>

                                    <td>
                                        <input type="text" name="rate[]" autocomplete="off"
                                            onkeypress='return (event.charCode == 46) || (event.charCode >= 48 && event.charCode <= 57)'
                                            value="{{ old('rate')[$key] ?? null }}"
                                            class="form-control rate" onkeyup="sub_total(this)">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control sub_total" name="subtotal[]"
                                            value="{{ old('subtotal')[$key] ?? null }}"
                                            readonly="readonly">
                                    </td>
                                    <td>
                                        <select name="supplier_id[]" class="form-control chosen-select">
                                            <option value="">Select</option>
                                            @foreach ($suppliers as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('supplier_id')[$key] ?? null == $id ? 'selected' : ' ' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="remarks[]"
                                            value="{{ old('remarks')[$key] ?? null }}"></td>
                                    {{-- <td>{{ optional(optional($purchase->last_receive)->purchase_receive_details[$key])->rate }}</td> --}}
                                    {{-- <td>{{ $purchase->last_receive->form_number }}</td> --}}
                                    {{-- <td> --}}
                                    {{-- @if ($requisition_from_item[$key]) --}}
                                    {{-- {{ $requisition_from_item[$key]['issue_quantity'] }}, --}}
                                    {{-- @endif --}}
                                    {{-- @foreach ($receive_items[$key] as $i => $receive_item) --}}
                                    {{-- {{ $receive_item->purchase_receive_details->first()->rate }}, --}}
                                    {{-- {{ $receive_items_quantity[$key][$i] }}, --}}
                                    {{-- @endforeach --}}

                                    {{-- </td> --}}
                                    {{-- <td> --}}
                                    {{-- @if (collect($requisition_number)[$key]) --}}
                                    {{-- <a target="_blank" href="{{ route('gin.list.show', optional(collect($requisition_number)[$key])->id) }}" >{{ optional(collect($requisition_number)[$key])->issue_number }}</a> --}}
                                    {{-- @endif --}}
                                    {{-- </td> --}}
                                    {{-- <td> --}}
                                    {{-- @if (collect($requisition_from_item)[$key]) --}}
                                    {{-- <a target="_blank" href="{{ route('items.show', optional($requisition_from_item[$key])['id']) }}" >Opening, </a> --}}
                                    {{-- @endif --}}
                                    {{-- @foreach ($receive_items[$key] as $i => $receive_item) --}}
                                    {{-- <a target="_blank" href="{{ route('grn.list.show', $receive_item->id) }}" >{{ $receive_item->form_number }},</a> --}}
                                    {{-- @endforeach --}}
                                    {{-- </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <input type="hidden" name="total" id="total" class="total" value="0">

            <div class="form-group">
                <div class="pull-right" style="padding-right:10px !important">
                    <button class="btn btn-success btn-sm" id="submitBtn"> <i class="fa fa-save"></i>
                        Save</button>
                    <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i>
                        Reset</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-info btn-sm"> <i
                            class="fa fa-list"></i> List</a>
                </div>
            </div>

        </form>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/jq_repeater.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>

    <!--datepicker plugin-->
    <script type="text/javascript">
        $(".date-picker").datepicker().datepicker("setDate", new Date());
        $('.date-picker').datepicker().on('changeDate', function(e) {
            $('.date-picker').datepicker('hide');
        });
        jQuery(function($) {

            $('.date-picker').datepicker({
                    autoclose: true,
                    format: 'dd-mm-yy',
                    todayHighlight: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function() {
                    $(this).prev().focus();
                });

        })
    </script>

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

    <script type="text/javascript">
        jQuery(function($) {

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
                                'width': '180px'
                            });
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if (event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': '180px'
                        });
                    })
                });


                $('#chosen-multiple-style .btn').on('click', function(e) {
                    var target = $(this).find('input[type=radio]');
                    var which = parseInt(target.val());
                    if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                    else $('#form-field-select-4').removeClass('tag-input-style');
                });
            }

        })
    </script>

    <script>
        function sub_total(element) {
            var row = $(element).closest('tr');
            var quantity = row.find('.quantity').val() > 0 ? row.find('.quantity').val() : 0;
            var rate = row.find('.rate').val() > 0 ? row.find('.rate').val() : 0;

            var sub_total = parseFloat(quantity) * parseFloat(rate);

            row.find('.sub_total').val(sub_total.toFixed(2));
            total();
            check_stock_exceeds(element);
        }



        function total() {
            var total = 0;
            $('.sub_total').each(function() {
                var sub_total = $(this).val() > 0 ? $(this).val() : 0;

                total += parseFloat(sub_total);
            });
            $('#total').val(total);
        }


        function load_item_stock(element) {
            var id = $(element).val();
            var row = $(element).closest('tr');

            $.ajax({
                url: '{{ url('ajax/item_stocks/get_item_stocks') }}',
                type: 'GET',
                data: 'id=' + id,
                success: function(res) {
                    row.find('.item_unit').val(res['item_unit']);
                    row.find('.already_received').val(res['purchase_received']);
                }
            });
        }



        $(document).ready(function() {
            var i = 1;
            $(".add_row").click(function() {
                b = i - 1;
                $('#addr' + i).html($('#addr' + b).html());
                $('#purchase_table').append('<tr id="addr' + (i + 1) + '"></tr>');
                i++;
            });
            $(".delete_row").click(function() {
                if (i > 1) {
                    $("#addr" + (i - 1)).html('');
                    i--;
                }
            });

        });



        function load_items(element) {
            var id = $(element).val();
            var row = $(element).closest('tr');

            $.ajax({
                url: '{{ url('generalstore/ajax/items/get-item-list') }}',
                type: 'GET',
                data: 'id=' + id,
                success: function(res) {
                    $('.item').empty();
                    $('.item').append('<option value="0">select</option>');
                    $.each(res['items'], function(id, name) {
                        $('.item').append('<option value="' + id + '">' + name + '</option>');
                    });
                }
            });
        }
    </script>

    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});
        });
    </script>

@stop
