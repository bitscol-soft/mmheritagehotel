@extends('layouts.master')
@section('title', 'Create Purchase')
@section('page-header')
    <i class="fa fa-gear"></i> Create Purchase Requisition
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

        @media print {
            .only-print {
                visibility: hidden;
            }

            #company_id {
                width: 120px !important;
            }
        }

    </style>
@stop


@section('content')

    <div class="row" id="purchase_form">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar only-print">
                        <a href="{{ route('rst.purchase.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Purchase List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('rst.purchase.store') }}" method="post"
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


                            <div class="form-group company">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Company </label>
                                <div class="col-xs-12 col-sm-8 @error('purchase_unit') has-error @enderror company">
                                    <select name="company_id" class="company" id="company_id"
                                        data-placeholder="-Select Company-" onchange="load_items(this)">
                                        <option></option>
                                        @foreach ($companies as $id => $company)
                                            <option value="{{ $id }}"
                                                {{ old('company_id') == $id ? 'selected' : '' }}>
                                                {{ $company }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('company_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>




                            <div class="form-group col-">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Date </label>
                                <div class="col-xs-12 col-sm-8 @error('purchase_date') has-error @enderror">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="purchase_date" id="id-date-picker-1"
                                            value="{{ old('purchase_date', date('d-m-Y')) }}"
                                            type="text" readonly/>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1">
                                    Reference </label>
                                <div class="col-xs-12 col-sm-8 @error('reference') has-error @enderror">
                                    <input type="number" step="0.01" class="form-control" name="reference"
                                        value="{{ old('reference') }}"
                                        placeholder="Reference">
                                    @error('reference')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <h3 class="header smaller lighter blue">Purchase Requisition</h3>
                                    <table id="purchase_table" class="table table-bordered edu1 container">
                                        <thead>
                                            <tr>
                                                <td rowspan="2" width="40%">Item</td>
                                                <td rowspan="2">Unit</td>
                                                <td rowspan="2">Stock</td>
                                                <td rowspan="2">Required Quantity</td>
                                                <td colspan="2" class="text-center">History</td>
                                                <td rowspan="2" colspan="2">Action</td>
                                            </tr>
                                            <tr>
                                                <td width="5%">Source</td>
                                                <td width="5%">Rate</td>
                                            </tr>
                                        </thead>
                                        <tbody class="">

                                            @if (old('item_id'))
                                                @foreach (old('item_id') as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <select name="item_id[]"
                                                                class="form-control item item'+ item_row + ' chosen-select"
                                                                onchange="load_item_stock(this)"
                                                                data-placeholder="-Select Item-">
                                                                <option></option>
                                                                @foreach ($items as $i => $item)
                                                                    @if ($item->company_id == old('company_id'))
                                                                        <option value="{{ $item->id }}"
                                                                            {{ old('item_id')[$key] == $item->id ? 'selected' : '' }}>
                                                                            {{ $item->name }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ old('item_unit_id')[$key] }}"
                                                                name="item_unit_id[]" class="form-control item_unit"
                                                                readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                value="{{ old('item_available_quantity')[$key] }}"
                                                                name="item_available_quantity[]"
                                                                class="form-control current_stock" readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text"
                                                                onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                                value="{{ old('quantity')[$key] }}" name="quantity[]"
                                                                class="form-control quantity" />
                                                        </td>
                                                        <td><span class="source"></span></td>
                                                        <td><span class="rate"></span></td>

                                                        <td><button type="button"
                                                                class="ibtnDel btn btn-sm btn-danger delete_row"
                                                                onclick="removeRow(this)"><i
                                                                    class="fa fa-times-circle"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <select name="item_id[]" class="form-control item chosen-select"
                                                            onchange="load_item_stock(this)" id="select20">

                                                            <option value="" disabled selected>select</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" name="item_unit_id[]"
                                                            class="form-control item_unit" readonly="readonly" />
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" name="item_available_quantity[]"
                                                            id="item_available_quantityq0"
                                                            class="form-control current_stock" readonly="readonly" />
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                            id="q0" value="" name="quantity[]"
                                                            class="form-control quantity" />
                                                    </td>
                                                    <td><span class="source"></span></td>
                                                    <td><span class="rate"></span></td>

                                                    <input type="hidden" name="sources[]" class="source_input">
                                                    <input type="hidden" name="rates[]" class="rate_input">

                                                    <td><button type="button"
                                                            class="ibtnDel btn btn-sm btn-danger delete_row"
                                                            onclick="removeRow(this)"><i
                                                                class="fa fa-times-circle"></i></button></td>
                                                </tr>
                                            @endif

                                            <tr id="addr1"></tr>
                                            <tr>
                                                <td colspan="9" style="text-align: right;">
                                                    <button type="button" onclick="insert_Row(this)"
                                                        class="btn btn-xs btn-inverse add_row r-btnAdd">
                                                        + Add New
                                                    </button>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <input type="hidden" id="total" value="0" name="total">


                            <div class="container">
                                <div class="row">
                                    <div style="margin-top:10px; margin-left:80px">
                                        <span class="only-print" id="print_btn"
                                            style="margin-right: 5px; cursor: pointer;">
                                            <img src="{{ asset('assets/images/export-icons/printer-icon.png') }}">
                                        </span>
                                        <div class="pull-right" style="padding-right: 80px !important;">
                                            @if (hasPermission('rst.purchase.create', $slugs))
                                                <button class="btn btn-success btn-sm pull-right"> <i
                                                        class="fa fa-save"></i> Save </button>
                                            @endif
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
    <script src="{{ asset('assets/custom_js/jq_repeater.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>



    <!-- printThis -->

    <script type="text/javascript" src="https://jasonday.github.io/printThis/printThis.js"></script>
    <script>
        $(document).on('ready', function() {

            if ($('#company_id').children('option').length <= 2) {
                let company_id = $('#company_id option:selected').val();

                load_items(company_id);

            }
        })


        $('#print_btn').on("click", function() {
            // $('#purchase_form').printThis({
            //     importCSS: true,
            // });
            print()
        });
        var item_row = 0;
        var items = [];




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

            if (count <= 1) {
                var row = $(element).closest('tr');

                $.ajax({
                    url: '{{ url('generalstore/ajax/item/get-item-details/purchase') }}',
                    type: 'GET',
                    data: 'id=' + id,
                    success: function(res) {
                        row.find('.item_unit').val(res['item_unit']);
                        row.find('.current_stock').val(res['current_stock']);

                        // // tracking info
                        if (res['last_receive'] != null) {
                            var last_receive = res['last_receive'];

                            var grn_url = "/gs/grn-list/" + last_receive.purchase_receive_id;
                            var source = '<a target="_blank" href="' + grn_url + '">' + res['receive_number'] +
                                '</a>, ';

                            row.find('.rate').text(last_receive.rate);
                            row.find('.source').html(source);

                            row.find('.rate_input').val(last_receive.rate);
                            row.find('.source_input').val(source);

                        } else {
                            row.find('.rate').text('');
                            row.find('.source').html('');

                            row.find('.rate_input').val('');
                            row.find('.source_input').val('');
                        }
                    }
                });
            }
        }


        // load items to the select box when change company
        function load_items(element) {
            var id = $(element).val() || element;
            var row = $(element).closest('tr');
            var route = "{{ route('rst.getItemList') }}"
            $.ajax({
                url: route,
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


        // insert new row of item
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
            var c7 = r.insertCell(6);

            var inputs = '<input type="hidden" name="sources[]" class="source_input">';
            inputs += '<input type="hidden" name="rates[]" class="issue_rate_input">';

            // populate product
            c1.innerHTML = '<select name="item_id[]" class="form-control item item' + item_row +
                ' chosen-select" onchange="load_item_stock(this)"  id="abc' + (item_row + 1) +
                '"  data-placeholder="-Select Item-"><option></option></select>';
            c2.innerHTML = '<input type="text" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />';
            c3.innerHTML = '<input type="text" id="item_available_quantityq"' + (item_row + 1) +
                ' name="item_available_quantity[]" class="form-control current_stock" readonly="readonly" />';
            c4.innerHTML =
                '<input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" id="q"' +
                (item_row + 1) + ' name="quantity[]" class="form-control quantity" />';

            c5.innerHTML = '<td><span class="source"></span></td>';
            c6.innerHTML = '<td><span class="rate"></span></td>' + inputs;
            c7.innerHTML =
                '<button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button>';

            // again add "+ Add New" Button
            var markup =
                '<tr><td colspan="9" style="text-align: right;"><button type="button" onclick="insert_Row(this)" class="btn btn-xs btn-inverse add_row r-btnAdd"> + Add New </button></td></tr>';
            $("table tbody").append(markup);
            $('.item' + item_row).empty();
            $('.item' + item_row).append('<option></option>');
            $.each(items, function(id, name) {
                $('.item' + item_row).append('<option value="' + id + '">' + name + '</option>');
            });
            // increment id
            item_row++;
            chosenTrigger()
        }


        // delete specifiv row
        function removeRow(el) {
            var item_row = $('#purchase_table tr').length;
            if (item_row > 4) {
                $(el).parents("tr").remove();
            }
        }
    </script>


    <script type="text/javascript">
        // <!--datepicker plugin-->
        jQuery(function($) {
            $('.date-picker').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    viewMode: "yyyy-mm-dd",
                    minViewMode: "yyyy-mm-dd",
                    todayHighlight: true
                })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function() {
                    $(this).prev().focus();
                });
        })
        // {{-- chosen select --}}
        $(() => chosenTrigger())

        function chosenTrigger() {
            jQuery(function($) {
                if (!ace.vars['touch']) {
                    $('#company_id').chosen({
                        allow_single_deselect: true
                    });
                    //resize the chosen on window resize
                    $(window)
                        .off('resize.chosen')
                        .on('resize.chosen', function() {
                            $('#company_id').each(function() {
                                var $this = $(this);
                                $this.next().css({
                                    'width': $this.parent().width()
                                });
                            })
                        }).trigger('resize.chosen');
                    //resize chosen on sidebar collapse/expand
                    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                        if (event_name != 'sidebar_collapsed') return;
                        $('#company_id').each(function() {
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
                                    'width': $this.parent().width()
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
    </script>

@stop
