@extends('layouts.master')
@section('title','Edit Purchase Requisition')
@section('page-header')
    <i class="fa fa-gear"></i> Edit Purchase Requisition
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
                        <i class="ace-icon fa fa-list-alt"></i> Purchase List
                    </a>
                </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('purchases.update', $purchase->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            @if ($errors->any())
                                <div class="alert alert-danger error">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            @if ($error != "The company id field is required." )
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


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Company </label>
                                <div class="col-xs-12 col-sm-8 @error('purchase_unit') has-error @enderror">
                                    <select name="company_id" class="form-control company_id" id="company_id" onchange="load_items(this)">
                                        <option value="" selected>select</option>
                                        @foreach($companies as $id => $company)
                                            <option value="{{ $id }}" {{ old('company_id') == $id || $purchase->company_id == $id ? 'selected' : '' }}>{{ $company }}</option>
                                        @endforeach
                                    </select>

                                    @error('company_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>





                            <div class="form-group col-">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Date </label>
                                <div  class="col-xs-12 col-sm-8 @error('purchase_date') has-error @enderror">
                                    <div class="input-group">
                                        <input class="form-control date-picker" name="purchase_date" value="{{ old('purchase_date') ?? \Carbon\Carbon::parse(now())->format('d-m-Y') }}" type="text" data-date-format="yyyy-mm-dd"/>
                                        <span class="input-group-addon">
                                        <i class="fa fa-calendar bigger-110"></i>
                                    </span>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> {{ $systemSetting->value != null ? $systemSetting->value : "Reference" }} </label>
                                <div class="col-xs-12 col-sm-8 @error('reference') has-error @enderror">
                                    <input type="number" step="0.01" class="form-control" name="reference" value="{{ old('reference') ?? $purchase->purchase_reference }}" placeholder="Reference">
                                    @error('reference')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- repeater -->

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <h3 class="header smaller lighter blue">Purchase Requisition</h3>
                                    <table id="purchase_table" class="table table-bordered edu1 container">
                                        <thead>
                                            <tr>
                                                <td width="40%">Item</td>
                                                <td>Unit</td>
                                                <td>Stock</td>
                                                <td class="text-center">Required Quantity</td>
                                                <td colspan="2" class="text-center">Action</td>
                                            </tr>
                                        </thead>


                                        <tbody class="">

                                            @if (old('item_id'))
                                                @foreach(old('item_id') as $key => $value)
                                                    <tr >
                                                        <td>
                                                            <select name="item_id[]" class="form-control item item'+ item_row + ' chosen-select" onchange="load_item_stock(this)">
                                                                <option value="">select</option>
                                                                @foreach($items as $i => $item)
                                                                    @if($item->company_id == old('company_id'))
                                                                        <option value="{{ $item->id }}" {{ old('item_id')[$key] == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>')
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ old('item_unit_id')[$key] }}" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ old('item_available_quantity')[$key] }}" name="item_available_quantity[]" class="form-control current_stock" readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" value="{{ old('quantity')[$key] }}" name="quantity[]" class="form-control quantity" />
                                                        </td>

                                                        <td><button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($purchase->purchase_details as $key => $detail)

                                                    <tr>
                                                        <td>
                                                            <select name="item_id[]" class="form-control item chosen-select" onchange="load_item_stock(this)" id="select20">
                                                                <option value="" disabled selected>select</option>
                                                                @foreach($items as $key => $item)
                                                                    @if($item->company_id == $purchase->company_id)
                                                                        <option value="{{ $item->id }}" {{ $item->id == $detail->item_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ $detail->item->item_unit->name }}" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{ $detail->item->current_stock }}" name="item_available_quantity[]" id="item_available_quantityq0" class="form-control current_stock" readonly="readonly" />
                                                        </td>
                                                        <td>
                                                            <input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" id="q0" value="{{ $detail->quantity }}" name="quantity[]" class="form-control quantity" />
                                                        </td>
                                                        <td><button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button></td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                        <tr id="addr1"></tr>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">
                                                <button type="button" onclick="insert_Row(this)" class="btn btn-xs btn-inverse add_row r-btnAdd">
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
                                    <div class="form-group">
                                        <div class="pull-right" style="padding-right: 40px !important;">
                                            @if(hasPermission('purchases.edit', $slugs))
                                                <button class="btn btn-success btn-sm"> <i class="fa fa-save"></i> Update </button>
                                            @endif
                                            <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i> Reset </button>
                                            @if(hasPermission('purchases.view', $slugs))
                                                <a href="{{ route('purchases.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-list"></i> List </a>
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

    


    <!--datepicker plugin-->
    <script type="text/javascript">

        // <!--datepicker plugin-->
        jQuery(function($) {
            $('.date-picker').datepicker({
                autoclose: true,
                format:'dd-mm-yyyy',
                todayHighlight: true
            });
        })

    </script>
    <script>
        var item_row = 0;
        var items = [];


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
                    url: '{{ url("generalstore/ajax/item/get-item-details/purchase") }}',
                    type: 'GET',
                    data: 'id=' + id,
                    success: function (res) {
                        row.find('.item_unit').val(res['item_unit']);
                        row.find('.current_stock').val(res['current_stock']);
                    }
                });
            }
        }


        function insert_Row(el) {
            // alert($(".company_id option:selected").val())
            var item_row = $('.item').length + 1;
            // first delete add item
            $(el).parents("tr").remove();


            // add new item row
            var r = document.getElementById('purchase_table').insertRow();

            var c1 = r.insertCell(0);
            var c2 = r.insertCell(1);
            var c3 = r.insertCell(2);
            var c4 = r.insertCell(3);
            var c5 = r.insertCell(4);

            // populate product

            c1.innerHTML = '<select name="item_id[]" class="form-control item item'+ item_row + ' chosen-select" onchange="load_item_stock(this)"></select>';


            c2.innerHTML = '<input type="text" name="item_unit_id[]" class="form-control item_unit" readonly="readonly" />';

            c3.innerHTML = '<input type="text" id="item_available_quantityq"' + item_row + ' name="item_available_quantity[]" class="form-control current_stock" readonly="readonly" />';

            c4.innerHTML = '<input type="text" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" id="q"' + item_row + ' name="quantity[]" class="form-control quantity" />';

            c5.innerHTML = '<button type="button" class="ibtnDel btn btn-sm btn-danger delete_row" onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button>';


            // again add "+ Add New" Button
            var markup = '<tr><td colspan="7" style="text-align: right;"><button type="button" onclick="insert_Row(this)" class="btn btn-xs btn-inverse add_row r-btnAdd"> + Add New </button></td></tr>';
            $("table tbody").append(markup);


            var company_id = $(".company_id option:selected").val();
            load_items(null, company_id, item_row);
            chosenTrigger()
        }

        // delete specifiv row
        function removeRow(el) {
            var item_row = $('#purchase_table tr').length;
            if (item_row>4) {
                $(el).parents("tr").remove();
            }

        }


        function load_items(element = null, company_id = null, item_row = null) {
            var id = company_id == null ? $(element).val() : company_id;
            var row = $(element).closest('tr');

            $.ajax({
                url: '{{ url("generalstore/ajax/items/get-item-list") }}',
                type: 'GET',
                data: 'id=' + id,
                success: function(res) {
                    if (item_row != null) {
                        $('.item'+item_row).append('<option value="">select</option>');
                        $.each(res['items'], function(id, name) {
                            $('.item'+item_row).append('<option value="' + id + '">' + name + '</option>').trigger('chosen:updated');
                        });
                    } else {
                        $('.item').empty()
                        $('.item').append('<option value="">select</option>');
                        $.each(res['items'], function(id, name) {
                            $('.item').append('<option value="' + id + '">' + name + '</option>').trigger('chosen:updated');
                        });
                    }
                    items = res['items'];
                }
            });
        }
    </script>


    <script type="text/javascript">
        $(() => chosenTrigger() )

        function chosenTrigger() {
            jQuery(function($){

                if(!ace.vars['touch']) {
                    $('#company_id').chosen({allow_single_deselect:true});
                    //resize the chosen on window resize

                    $(window)
                        .off('resize.chosen')
                        .on('resize.chosen', function() {
                            $('#company_id').each(function() {
                                var $this = $(this);
                                $this.next().css({'width': $this.parent().width()});
                            })
                        }).trigger('resize.chosen');
                    //resize chosen on sidebar collapse/expand
                    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                        if(event_name != 'sidebar_collapsed') return;
                        $('#company_id').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': $this.parent().width()});
                        })
                    });


                    $('#chosen-multiple-style .btn').on('click', function(e){
                        var target = $(this).find('input[type=radio]');
                        var which = parseInt(target.val());
                        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                        else $('#form-field-select-4').removeClass('tag-input-style');
                    });
                }


                if(!ace.vars['touch']) {
                    $('.chosen-select').chosen({allow_single_deselect:true});
                    //resize the chosen on window resize

                    $(window)
                        .off('resize.chosen')
                        .on('resize.chosen', function() {
                            $('.chosen-select').each(function() {
                                var $this = $(this);
                                $this.next().css({'width': $this.parent().width()});
                            })
                        }).trigger('resize.chosen');
                    //resize chosen on sidebar collapse/expand
                    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                        if(event_name != 'sidebar_collapsed') return;
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': $this.parent().width()});
                        })
                    });
                }

            })
        }
    </script>


@stop
