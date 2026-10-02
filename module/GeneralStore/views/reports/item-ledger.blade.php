@extends('layouts.master')
@section('title','Item Ledger')


@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />


    <style>
        select.required:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        .bg-header {
            background-color: aliceblue !important;
        }
    </style>
@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-gs mm-rst mm-rst-inv" title="Item ledger" description="Stock movement of one item over a period.">
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')



        <form class="form-horizontal" action="{{ route('item_details') }}" method="get">
            <div class="row">
                @csrf
                <div class="col-sm-12">
                    <x-mm.table-scroll label="Item ledger">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon">Company</span>
                                        <select name="company_id" class="form-control company_id chosen-select" onchange="load_items(this)">
                                            <option selected disabled>select</option>
                                            @foreach($companies as $id => $name)
                                                <option value="{{ $id }}" {{ request()->company_id == $id ? 'selected':'' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </td>
                                <td width="320px">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm date-picker" name="from_date" value="{{ request('from_date') }}" autocomplete="off">
                                        <span class="input-group-addon">From|To</span>
                                        <input type="text" class="form-control input-sm date-picker" name="to_date" value="{{ request('to_date') }}" autocomplete="off">
                                    </div>
                                </td>
            {{--                    <td width="200px">--}}
            {{--                        <div class="input-group">--}}
            {{--                            <span class="input-group-addon">Item</span>--}}
            {{--                            <select name="item_id" class="form-control item chosen-select required" required="required">--}}
            {{--                                <option  value="">select</option>--}}
            {{--                                @if (isset($items))--}}
            {{--                                    @foreach($items as $id => $item)--}}
            {{--                                        <option value="{{ $id }}" {{ request()->item_id == $id ? 'selected':'' }}>{{ $item }}</option>--}}
            {{--                                    @endforeach--}}
            {{--                                @endif--}}
            {{--                            </select>--}}
            {{--                        </div>--}}
            {{--                    </td>--}}

                                <td>
                                    <div class="input-group">
                                        <label for="inputError" class="input-group-addon"> Item</label>
                                        <input type="text" name="item_id" class="form-control items required" required="required" value="{{ request('item_id') }}"/>
            {{--                            <input type="hidden" name="item_id" class="form-control items-id required" required="required"/>--}}
                                        <div class="space-4"></div>
                                    </div>
                                </td>

                                <td width="200px">
                                    <div class="btn-group btn-corner">
                                        <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                        <a href="{{ route('item_details') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4" class="text-center" style="font-size:20px"><strong>@if(isset($selected_item)) {{ "'".$selected_item->name."' -" }} @endif Stock Details</strong></td>
                            </tr>
                        </table>
                    </x-mm.table-scroll>
                </div>
            </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-xs-12">

                <x-mm.table-scroll label="Item ledger">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center bg-header" rowspan="2" style="width:100px !important">Date</th>
                                <th class="text-center bg-header" colspan="3">Opening Balance</th>
                                <th class="text-center bg-header" colspan="4">Receive</th>
                                <th class="text-center bg-header" colspan="4">Issue</th>
                                <th class="text-center bg-header" colspan="3">Closing Balance</th>
                            </tr>
                            <tr>
                                <th class="bg-header text-center">Qty</th>
                                <th class="bg-header text-right">Rate</th>
                                <th class="bg-header text-right">Amount</th>
                                <th class="bg-header" style="width:130px !important;">GRN</th>
                                <th class="bg-header text-center">Qty</th>
                                <th class="bg-header text-right">Rate</th>
                                <th class="bg-header text-right">Amount</th>
                                <th class="bg-header">GIN</th>
                                <th class="bg-header text-center">Qty</th>
                                <th class="bg-header text-right">Rate</th>
                                <th class="bg-header text-right">Amount</th>
                                <th class="bg-header text-center">Qty</th>
                                <th class="bg-header text-right">Rate</th>
                                <th class="bg-header text-right">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (isset($item_stock_details))
                                @php
                                    $opening_qty     = (int)$opening_stock;
                                    $opening_cost    = $opening_rate;
                                    $opening_amount  = $opening_rate * $opening_stock;

                                @endphp


                                @forelse ($item_stock_details as $key => $details)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($details->date)->format('Y-m-d') }}</td>
                                        <td class="text-center">{{ number_format($opening_qty, 2)  }}</td>
                                        <td class="text-right">{{ number_format($opening_cost, 2) }}</td>
                                        <td class="text-right">{{ round($opening_amount, 2)   }}</td>

                                        @if ($details->type == "Purchase Receive")
                                            <td>{{ $details->source_number }}</td>
                                            <td class="text-center">{{ number_format($details->credit_qty, 2) }}</td>
                                            <td class="text-right">{{ number_format($details->credit_rate, 2) }}</td>
                                            <td class="text-right">{{ number_format($details->credit_qty * $details->credit_rate, 2)  }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $details->source_number }}</td>
                                            <td class="text-center">{{ number_format($details->debit_qty, 2) }}</td>
                                            <td class="text-right">{{ number_format($details->debit_rate, 2) }}</td>
                                            <td class="text-right">{{ number_format(($details->debit_qty * $details->debit_rate), 2) }}</td>
                                        @endif

                                        @php
                                            //$opening_qty = requesr()->params ? request()->params:$opening_qty;

                                            $final_qty = $opening_qty + $details->credit_qty - $details->debit_qty;
                                            $final_amount = (($opening_qty * $opening_cost) + ($details->credit_qty * $details->credit_rate) - ($details->debit_qty * $details->debit_rate));
                                            if ($final_qty != 0) {
                                                $final_rate = $final_amount / $final_qty;
                                            } else {
                                                $final_rate = 0;
                                                $final_amount = 0;
                                            }

                                            $opening_qty     = $final_qty;
                                            $opening_cost    = $final_rate;
                                            $opening_amount  = $final_amount;
                                        @endphp

                                        <td>{{ number_format($final_qty, 2) }}</td>
                                        <td class="text-right">{{ number_format($final_rate, 2) }}</td>
                                        <td class="text-right">{{ number_format($final_amount, 2) }}</td>
                                    </tr>

                                        <input type="hidden" name="last_qty" value="{{ $final_qty }}">
                                        <input type="hidden" name="last_cost" value="{{ $opening_cost }}">
                                        <input type="hidden" name="last_amount" value="{{ $opening_amount }}">

                                    @empty
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($selected_item ? $selected_item->created_at : '')->format('y-m-d') }}</td>
                                        <td class="text-center">{{ $opening_stock }}</td>
                                        <td class="text-right">{{ number_format($opening_cost, 2) }}</td>
                                        <td class="text-right">{{ number_format($opening_rate, 2) }}</td>


                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td class="text-center">{{ $opening_stock }}</td>
                                        <td class="text-right">{{ number_format($opening_rate, 2) }}</td>
                                        <td class="text-right">{{ number_format($opening_rate, 2) }}</td>

                                    </tr>
                                @endforelse

                                @if (count($item_stock_details) == 0 && $opening_cost == 0)
                                    <tr>
                                        <td colspan="15" class="text-center">
                                            <b class="text-danger">No records found!</b>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">
                                        <b class="text-danger">No records found!</b>
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </x-mm.table-scroll>


            </div>
        </div>

        </form>
        @if (isset($item_stock_details))
            @include('reports.gs-paginate', ['data' => $item_stock_details])

            <div class="pull-left" style="margin-top:10px; margin-left:10px">
                <span onclick="exportData('{{ url('export-item-details-excel') }}')" style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                </span>
                    <span onclick="exportData('{{ url('export-gs-item-details-pdf') }}')" style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                </span>
            </div>

            <form class="exportForm" method="POST">
                @csrf
                <input type="hidden" name="model" value="Stock Details">
                <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                <input type="hidden" name="item_id" class="item_id" value="{{ request('item_id') }}">
                <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                <input type="hidden" name="to_date" value="{{ request('to_date') }}">
            </form>
        @endif
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>


    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    

<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/spinbox.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.knob.min.js') }}"></script>
<script src="{{ asset('assets/js/autosize.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-tag.min.js') }}"></script>


<script type="text/javascript">
    function exportData(url)
    {
        $('.exportForm').attr('action', url).submit();
    }
</script>
<!-- inline scripts related to this page -->

<script type="text/javascript">

    jQuery(function($){

        if(!ace.vars['touch']) {
            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': '200px'});
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if(event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': '200px'});
                })
            });


            $('#chosen-multiple-style .btn').on('click', function(e){
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }

    })

    // data table
    $('#dynamic-table').DataTable({
        "ordering": false,
        "bPaginate": false,
        "lengthChange": false,
        "info": false,
        'searching': false
    });

    // data picker
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format:'yyyy-mm-dd',
    });

</script>


<script type="text/javascript">

    //load items by company
    function load_items(element) {
        var id = $(element).val();
        var row = $(element).closest('tr');

        $.ajax({
            url: '{{ url("generalstore/ajax/items/get-item-list") }}',
            type: 'GET',
            data: 'id=' + id,
            success: function(res) {
                $('.item').empty();
                $('.item').append('<option value="">select</option>');
                $.each(res['items'], function(id, name) {
                    $('.item').append('<option value="' + id + '">' + name + '</option>').trigger('chosen:updated');
                });
                items = res['items'];
            }
        });
    }


    //load items by company
    function load_items(element) {
        var id = $(element).val();
        var row = $(element).closest('tr');

        $.ajax({
            url: '{{ url("generalstore/ajax/items/get-item-list") }}',
            type: 'GET',
            data: 'id=' + id,
            success: function(res) {
                $('.item').empty();
                $('.item').append('<option value="">select</option>');
                $.each(res['items'], function(id, name) {
                    $('.item').append('<option value="' + id + '">' + name + '</option>').trigger('chosen:updated');
                });
                items = res['items'];
            }
        });
    }

</script>

<!--autocomplete-->
<script type="text/javascript">
    $('.items').keyup(function () {
        var items = [];

        var company_id = $('.company_id').val();
        var item_name  = $('.items').val();

        $.ajax({
            url: '{{ url("ajax/items/get-item-list-by-type") }}',
            type: 'GET',
            data: {
                company_id: company_id,
                name: item_name
            },
            success: function(res) {
                $.each(res['items'], function(id, name) {
                    // items.push({
                    //         label: name,
                    //         value: id,
                    //      });
                    items.push(name);
                });
            }
        });
        $( ".items" ).autocomplete({
            source: items
        });
    });
</script>

@stop
