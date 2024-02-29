@extends('layouts.master')
@section('title', 'Goods Requisition')
@section('page-header')
    <i class="fa fa-gear"></i> Create Goods Requisition
@stop
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


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('purchases.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Goods Requisition List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('goods-requisitions.store') }}" method="post">
                            @csrf

                            @include('partials._alert_message')

                            <!-- select company -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Company </label>
                                <div class="col-xs-12 col-sm-8 @error('purchase_unit') has-error @enderror">
                                    <select name="company_id" class="form-control filter" id="company_id"
                                        onchange="load_items(this)" data-placeholder="-Select Company-">
                                        <option></option>
                                        @foreach ($companies as $id => $company)
                                            <option value="{{ $id }}"
                                                {{ old('company_id') == $id ? 'selected' : '' }}>{{ $company }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('company_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- select department -->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Department</label>
                                <div class="col-xs-12 col-sm-8 @error('department_id') has-error @enderror">
                                    <select name="department_id" class="form-control filter">
                                        <option value="">select</option>
                                        @foreach ($departments as $id => $department)
                                            <option value="{{ $id }}"
                                                {{ old('department_id') == $id ? 'selected' : '' }}>{{ $department }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('department_id')
                                        <span class="text-danger"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- select date -->
                            <div class="form-group col-">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Date </label>
                                <div class="col-xs-12 col-sm-8 @error('date') has-error @enderror">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="date" id="id-date-picker-1"
                                            autocomplete="off" type="text" data-date-format="yyyy-mm-dd"
                                            value="{{ old('date') }}" />
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- reference -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1">
                                    {{ $systemSetting->value != null ? $systemSetting->value : 'Reference' }} </label>
                                <div class="col-xs-12 col-sm-8 @error('goods_requisition_reference') has-error @enderror">
                                    <input type="number" step="0.01" class="form-control"
                                        name="goods_requisition_reference"
                                        value="{{ old('goods_requisition_reference') }}"
                                        placeholder="{{ $systemSetting->value != null ? $systemSetting->value : 'Reference' }}">

                                    @error('goods_requisition_reference')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>




                            <!-- product entry form -->
                            <div class="row text-center">
                                <div class="col-sm-12">
                                    <h3 class="header smaller lighter blue">Goods Requisition</h3>
                                    <table id="purchase_table" class="table table-bordered edu1 container">
                                        <!-- title head -->
                                        <thead>
                                            <tr>
                                                <td rowspan="2">Item</td>
                                                <td rowspan="2">Unit</td>
                                                <td rowspan="2">Stock</td>
                                                <td rowspan="2">Quantity</td>
                                                <td rowspan="2">Remarks</td>
                                                <td colspan="4" class="text-center">History</td>
                                                <td rowspan="2" colspan="2">Action</td>
                                            </tr>
                                            <tr>
                                                <td width="5%">GIN</td>
                                                <td width="5%">Source</td>
                                                <td width="5%">Rate</td>
                                                <td width="5%">Qty</td>
                                            </tr>
                                        </thead>




                                        <tbody class="text-left">
                                            @if (old('item_id'))
                                                @foreach (old('item_id') as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <select name="item_id[]" id="itemsDropdown"
                                                                class="form-control item chosen-select"
                                                                onchange="load_item_stock(this)"
                                                                id="select2{{ $key }}"  data-placeholder="-Select Item-">
                                                                <option></option>

                                                                @foreach ($items->where('company_id', old('company_id')) as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ old('item_id')[$key] ?? null == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text"
                                                                value="{{ old('item_unit_id')[$key] ?? null }}"
                                                                name="item_unit_id[]" class="form-control item_unit"
                                                                readonly="readonly" /></td>
                                                        <td>
                                                            <input type="text"
                                                                value="{{ old('item_available_quantity')[$key] ?? null }}"
                                                                name="item_available_quantity[]"
                                                                class="form-control current_stock" readonly="readonly" />
                                                        </td>
                                                        <td><input type="number" onkeyup="checkQtyLimit(this) "
                                                                onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                                value="{{ old('quantity')[$key] }}" name="quantity[]"
                                                                class="form-control quantity" /></td>
                                                        <td><input type="text" class="form-control" name="remarks[]"
                                                                value="{{ old('remarks')[$key] ?? null }}"></td>

                                                        <td><span class="issue_number">{!! old('issue_number_input')[$key] ?? null !!}</span></td>
                                                        <td><span class="source">{!! old('source_input')[$key] ?? null !!}</span></td>
                                                        <td><span class="issue_rate">{!! old('issue_rate_input')[$key] ?? null !!}</span></td>
                                                        <td><span class="issue_quantity">{!! old('issue_quantity_input')[$key] ?? null !!}</span></td>

                                                        <td><button type="button"
                                                                class="ibtnDel btn btn-sm btn-danger delete_row"
                                                                onclick="removeRow(this)"><i
                                                                    class="fa fa-times-circle"></i></button></td>


                                                        <input type="hidden" name="issue_number[]"
                                                            class="issue_number_input"
                                                            value="{{ old('issue_number_input')[$key] ?? null }}">
                                                        <input type="hidden" name="source[]" class="source_input"
                                                            value="{{ old('source_input')[$key] ?? null }}">
                                                        <input type="hidden" name="issue_rates[]" class="issue_rate_input"
                                                            value="{{ old('issue_rate_input')[$key] ?? null }}">
                                                        <input type="hidden" name="issue_quantities[]"
                                                            class="issue_quantity_input"
                                                            value="{{ old('issue_quantity_input')[$key] ?? null }}">
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
                                                            class="form-control current_stock available-qty"
                                                            readonly="readonly" />
                                                    </td>
                                                    <td>
                                                        <input type="text" id="q0" value="" onkeyup="checkQtyLimit(this)"
                                                            onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                            name="quantity[]" class="form-control quantity" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="remarks[]" value="">
                                                    </td>
                                                    <td><span class="issue_number"></span></td>
                                                    <td><span class="source"></span></td>
                                                    <td><span class="issue_rate"></span></td>
                                                    <td><span class="issue_quantity"></span></td>

                                                    <input type="hidden" name="issue_number[]" class="issue_number_input">
                                                    <input type="hidden" name="source[]" class="source_input">
                                                    <input type="hidden" name="issue_rates[]" class="issue_rate_input">
                                                    <input type="hidden" name="issue_quantities[]"
                                                        class="issue_quantity_input">

                                                    <td><button type="button"
                                                            class="ibtnDel btn btn-sm btn-danger delete_row"
                                                            onclick="removeRow(this)"><i
                                                                class="fa fa-times-circle"></i></button></td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td colspan="11" style="text-align: right;">
                                                    <button type="button" onclick="insert_Row(this)"
                                                        class="btn btn-xs btn-inverse add_row r-btnAdd">
                                                        + Add New
                                                    </button>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <div class="pull-right" style="padding-right: 10px !important;">
                                            <button class="btn btn-success btn-sm"> <i class="fa fa-save"></i>
                                                Save</button>
                                            <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i>
                                                Reset</button>
                                            <a href="{{ route('goods-requisitions.index') }}"
                                                class="btn btn-info btn-sm"> <i class="fa fa-list"></i> List</a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <input type="hidden" id="total" value="0" name="total">


                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/jq_repeater.js') }}"></script>


    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>



    <!--datepicker plugin-->
    <script type="text/javascript">
        // $(".date-picker").datepicker().datepicker("setDate", new Date());
        $('.date-picker').datepicker().on('changeDate', function(e) {
            $('.date-picker').datepicker('hide');
        });




        $(document).on('ready', function() {

            if ($('#company_id').children('option').length <= 2) {
                let company_id = $('#company_id option:selected').val();

                load_items(company_id);

            }
        })




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



    <script>
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
            var c7 = r.insertCell(6);
            var c8 = r.insertCell(7);
            var c9 = r.insertCell(8);
            var c10 = r.insertCell(9);



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

            c6.innerHTML = '<td><span class="issue_number"></span></td>';
            c7.innerHTML = '<td><span class="source"></span></td>' + inputs;
            c8.innerHTML = '<td><span class="issue_rate"></span></td>';
            c9.innerHTML = '<td><span class="issue_quantity"></span></td>';

            c10.innerHTML =
                '<button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button>';


            // again add "+ Add New" Button
            var markup =
                '<tr><td colspan="11" style="text-align: right;"><button type="button" onclick="insert_Row(this)" class="btn btn-xs btn-inverse add_row r-btnAdd"> + Add New </button></td></tr>';
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
                url: '{{ url('generalstore/ajax/items/get-item-list') }}',
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
                    url: '{{ url('generalstore/ajax/item/get-item-details/purchase') }}',
                    type: 'GET',
                    data: 'id=' + id,
                    success: function(res) {


                        // tracking info
                        if (res['receive_items'] != null) {
                            // gin number
                            if (res['requisition_number'] != null) {
                                var gin_url = '/gs/gin-list/' + res['requisition_number'].id;


                                // manage item
                                var rates = "";
                                var source = "";
                                var opening_url = ""
                                var grn_numbers = "";
                                var issue_quantities = "";

                                if (res['requisition_from_item'].length != 0) {
                                    rates += res['requisition_from_item'].rate + ", ";
                                    issue_quantities += res['requisition_from_item'].issue_quantity + ", ";
                                    opening_url = "/gs/items/" + id;
                                    source += '<a target="_blank" href="' + opening_url + '"> Opening </a>, ';
                                }

                                if (res['receive_items'].length != 0) {
                                    $.each(res['receive_items'], function(key, value) {
                                        var grn_url = "/gs/grn-list/" + value.id;
                                        source += '<a target="_blank" href="' + grn_url + '">' + value
                                            .form_number + '</a>, ';
                                        issue_quantities += res['receive_items_quantity'][key] + ", ";
                                        rates += value.purchase_receive_details[0].rate + ", ";
                                    });
                                }
                                row.find('.issue_number').html('<a target="_blank" href="' + gin_url + '">' +
                                    res['requisition_number'].issue_number + '</a>');
                                row.find('.issue_rate').text(rates);
                                row.find('.issue_quantity').text(issue_quantities);
                                row.find('.source').html(source);

                                row.find('.issue_number_input').val('<a target="_blank" href="' + gin_url +
                                    '">' + res['requisition_number'].issue_number + '</a>');
                                row.find('.issue_rate_input').val(rates);
                                row.find('.issue_quantity_input').val(issue_quantities);
                                row.find('.source_input').val(source);
                            } else {
                                row.find('.issue_number').text('');
                                row.find('.issue_rate').text('');
                                row.find('.issue_quantity').text('');
                                row.find('.source').html('');

                                row.find('.issue_number_input').val('');
                                row.find('.issue_rate_input').val('');
                                row.find('.issue_quantity_input').val('');
                                row.find('.source_input').val('');
                            }

                        } else {
                            row.find('.issue_number').text('');
                            row.find('.issue_rate').text('');
                            row.find('.issue_quantity').text('');
                            row.find('.source').html('');

                            row.find('.issue_number_input').val('');
                            row.find('.issue_rate_input').val('');
                            row.find('.issue_quantity_input').val('');
                            row.find('.source_input').val('');
                        }
                        row.find('.item_unit').val(res['item_unit']);
                        row.find('.current_stock').val(res['current_stock']);
                    }
                });
            }
        }
    </script>


    {{-- chosen select --}}
    <script type="text/javascript">
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


        function checkQtyLimit(object) {
            let current_stock = Number($(object).closest('tr').find('.current_stock').val() | 0)
            let qty = Number($(object).val() | 0)

            if (qty > current_stock) {
                showAlertMessage('Limit Up!', 2000)
                $(object).val(0)
            }
        }
    </script>


@stop
