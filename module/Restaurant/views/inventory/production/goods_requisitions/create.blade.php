@extends('layouts.master')
@section('title', 'Production')
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

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-inv mm-rst-form" title="Goods requisition" description="Pick materials and the finished goods they produce.">
    <x-slot name="actions">
        <a href="{{ route('rst.production.index') }}" class="mm-button">
            <i class="ace-icon fa fa-list-alt"></i> Production List
        </a>

    </x-slot>
    <x-mm.panel class="tw-p-4">
        <form class="form-horizontal" action="{{ route('rst.production.store') }}" method="post">
            @csrf

            @include('partials._alert_message')

            <!-- select company -->
            {{-- <div class="form-group">
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
            </div> --}}

            <!-- select department -->
            {{-- <div class="form-group">
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
            </div> --}}

            <!-- select date -->
            <div class="form-group col-">
                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Date </label>
                <div class="col-xs-12 col-sm-8 @error('date') has-error @enderror">
                    <div class="input-group">
                        <input class="form-control date-picker" name="date" id="id-date-picker-1"
                            autocomplete="off" type="text" data-date-format="yyyy-mm-dd"
                            value="{{ old('date', date('Y-m-d')) }}" />
                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- reference -->
            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1-1"> Reference </label>
                <div class="col-xs-12 col-sm-8 @error('challan_id') has-error @enderror">
                    <input type="text" class="form-control"
                        name="challan_id"
                        value="{{ $challan_id }}"
                        placeholder="Reference">

                    @error('challan_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row Matrial Entry form -->

                    <h3 class="header smaller lighter blue">Row Materials</h3>
                    <x-mm.table-scroll label="Materials">
                        <table id="purchase_table" class="table table-bordered edu1 container">
                            <!-- title head -->
                            <thead>
                                <tr>
                                    <td rowspan="2">Item</td>
                                    <td rowspan="2">Unit</td>
                                    <td rowspan="2">Stock</td>
                                    <td rowspan="2">Quantity</td>
                                    <td rowspan="2">Remarks</td>
                                    <td rowspan="2">Action</td>
                                </tr>
                            </thead>
    
                            <tbody class="text-left">
                                    <tr>
                                        <td>
                                            <select name="material_id[]" class="form-control item chosen-select"
                                                onchange="load_item_stock(this)" id="select20">
                                                <option value="" disabled selected>select</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" value="" name="material_unit_id[]"
                                                class="form-control material_item_unit" readonly="readonly" />
                                        </td>
                                        <td>
                                            <input type="text" value="" name="material_available_quantity[]"
                                                id="item_available_quantityq0"
                                                class="form-control material_current_stock material-available-qty"
                                                readonly="readonly" />
                                        </td>
                                        <td>
                                            <input type="text" id="q0" value="" onkeyup="checkQtyLimit(this)"
                                                onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                name="material_quantity[]" class="form-control material_quantity" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="material_remarks[]" value="">
                                        </td>
    
                                        <td><button type="button"
                                                class="ibtnDel btn btn-sm btn-danger delete_row"
                                                onclick="removeRow(this)"><i
                                                    class="fa fa-times-circle"></i></button></td>
                                    </tr>
    
                                <tr>
                                    <td colspan="7" style="text-align: right;">
                                        <button type="button" onclick="insert_Row(this)"
                                            class="btn btn-xs btn-inverse add_row r-btnAdd">
                                            + Add New
                                        </button>
                                    </td>
                                </tr>
    
                            </tbody>
                        </table>
                    </x-mm.table-scroll>

            <!-- Finish Good Entry form -->

                    <h3 class="header smaller lighter blue">Finish Good</h3>
                    <x-mm.table-scroll label="Finished goods">
                        <table id="finish_good_table" class="table table-bordered edu1 container">
                            <!-- title head -->
                            <thead>
                                <tr>
                                    <td rowspan="2">Item</td>
                                    <td rowspan="2">Unit</td>
                                    <td rowspan="2">Stock</td>
                                    <td rowspan="2">Quantity</td>
                                    <td rowspan="2">Remarks</td>
                                    <td rowspan="2">Action</td>
                                </tr>
                            </thead>
    
                            <tbody class="text-left">
    
                                    <tr>
                                        <td>
                                            <select name="item_id[]" class="form-control item2 chosen-select"
                                                onchange="load_product_stock(this)" id="select20">
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
                                                class="form-control item_current_stock item-available-qty"
                                                readonly="readonly" />
                                        </td>
                                        <td>
                                            <input type="text" id="q0" value="" onkeyup="checkItemQtyLimit(this)"
                                                onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                                name="item_quantity[]" class="form-control item_quantity" />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="item_remarks[]" value="">
                                        </td>
    
                                        <td><button type="button"
                                                class="ibtnDel-2 btn btn-sm btn-danger delete_row_2"
                                                onclick="removeRow_2(this)"><i
                                                    class="fa fa-times-circle"></i></button></td>
                                    </tr>
    
                                <tr>
                                    <td colspan="7" style="text-align: right;">
                                        <button type="button" onclick="insert_Row_2(this)"
                                            class="btn btn-xs btn-inverse add_row_2 r-btnAdd-2">
                                            + Add New
                                        </button>
                                    </td>
                                </tr>
    
                            </tbody>
                        </table>
                    </x-mm.table-scroll>
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

            <input type="hidden" id="total" value="0" name="total">

        </form>

    </x-mm.panel>
</x-mm.page>

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

                load_items();
                load_products();
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

        var item_row_2 = 0;
        var items_2 = [];

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

            chosenTrigger()
        }

        // insert new row
        function insert_Row_2(el) {
            // first delete add item
            $(el).parents("tr").remove();

            // add new item row
            var f = document.getElementById('finish_good_table').insertRow();

            var g1 = f.insertCell(0);
            var g2 = f.insertCell(1);
            var g3 = f.insertCell(2);
            var g4 = f.insertCell(3);
            var g5 = f.insertCell(4);
            var g6 = f.insertCell(5);

            var inputs =
                '<input type="hidden" name="issue_number[]" class="issue_number_input"><input type="hidden" name="source[]" class="source_input"><input type="hidden" name="issue_rates[]" class="issue_rate_input"><input type="hidden" name="issue_quantities[]" class="issue_quantity_input">';

            // populate product
            g1.innerHTML = '<select name="item_id[]" class="form-control item2 item2' + item_row_2 +
                ' chosen-select" onchange="load_item_stock(this)" id="abc' + (item_row_2 + 1) +
                '" data-placeholder="-Select Item-"><option></option></select>';

            g2.innerHTML = '<input type="text" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />';

            g3.innerHTML = '<input type="text" id="item_available_quantityq"' + (item_row_2 + 1) +
                ' name="item_available_quantity[]" class="form-control current_stock" readonly="readonly" />';

            g4.innerHTML =
                '<input onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" type="text" id="q"' +
                (item_row_2 + 1) + ' name="item_quantity[]" onkeyup="checkItemQtyLimit(this)" class="form-control quantity"/>';
            g5.innerHTML = '<input type="text" class="form-control" name="item_remarks[]" value="">';

            g6.innerHTML =
                '<button type="button" class="ibtnDel-2 btn btn-sm btn-danger delete_row_2" onclick="removeRow_2(this)"><i class="fa fa-times-circle"></i></button>';

            // again add "+ Add New" Button
            var markup_2 =
                '<tr><td colspan="11" style="text-align: right;"><button type="button" onclick="insert_Row_2(this)" class="btn btn-xs btn-inverse add_row_2 r-btnAdd-2"> + Add New </button></td></tr>';
            $("table#finish_good_table tbody").append(markup_2);

            $('.item2' + item_row_2).empty();
            $('.item2' + item_row_2).append('<option></option>');
            $.each(items_2, function(id, name) {
                $('.item2' + item_row_2).append('<option value="' + id + '">' + name + '</option>');
            });

            // increment id
            item_row_2++;

            chosenTrigger()
        }

        // delete specific row
        function removeRow(el) {
            var item_row = $('#purchase_table tr').length;
            if (item_row > 4) {
                $(el).parents("tr").remove();
            }

        }

        // delete specific row
        function removeRow_2(el) {
            var item_row_f = $('#finish_good_table tr').length;
            if (item_row_f > 4) {
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

                        row.find('.material_item_unit').val(res['mat_item_unit']);
                        row.find('.material_current_stock').val(res['mat_current_stock']);
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

    </script>

@stop
