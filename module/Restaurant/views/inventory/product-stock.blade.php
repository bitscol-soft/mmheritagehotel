@extends('layouts.master')
@section('title','Inventory Report')
<!-- <i class="fa fa-list"></i> Inventory Reports -->

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-report-page mm-rst" title="Inventory Report" :subtitle="'(' . $item_stocks->total() . ' Records Found, page ' . (request('page') ?? 1) . ' of ' . $item_stocks->lastPage() . ', Data Show per page ' . $item_stocks->perPage() . ')'">
        <x-mm.panel>
    <div class="row">
        <form class="form-horizontal" action="{{ route('items_stock') }}" method="get">

            <div class="col-sm-12">
                <table class="table table-bordered">

                    <tr>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">Company</span>
                                <select name="company_id" id="company_id" class="form-control chosen-select-180" onchange="loadCompanyItems()">
                                    <option selected disabled>select</option>
                                    @foreach($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request()->company_id == $id ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon">Unit</span>
                                <select name="unit_id" class="form-control chosen-select-180">
                                    <option selected value="">select</option>
                                    @foreach($units as $id => $name)
                                        <option value="{{ $id }}" {{ request()->unit_id == $id ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td width="350px">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm date-picker" name="from_date" value="{{ request('from_date') }}" autocomplete="off">
                                <span class="input-group-addon">From|To</span>
                                <input type="text" class="form-control input-sm date-picker" name="to_date" value="{{ request('to_date') }}" autocomplete="off">
                            </div>
                        </td>
                        <td width="200px">
                            <div class="input-group">
                                <span class="input-group-addon">Item</span>
                                <select name="item_id" class="form-control chosen-select" id="item_id">
                                    <option selected value="">select</option>
                                    @foreach($items as $id => $item)
                                        <option value="{{ $id }}" {{ request()->item_id == $id ? 'selected':'' }}>{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td width="180px">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('items_stock') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-center" style="font-size:20px"><strong>Stock In Hand</strong></td>
                    </tr>

                </table>
            </div>
        </form>
    </div>



<div class="clearfix"></div>

<div class="row">
    <div class="col-xs-12">
        <table id="dynamic-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr style="background: #C9DAF8 !important; color:black !important">
                    <th>SL</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Unit</th>
                    <th>Company</th>
                    <th class="text-center">Stock In Hand</th>
                </tr>
            </thead>

            <tbody>
                @foreach($item_stocks as $key => $item)
                    <tr>
                        <td>{{ $key+$item_stocks->firstItem() }}</td>
                        <td style="font-weight: bold !important;">{{ fdate($item->created_at) }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ optional($item->item_unit)->name }}</td>
                        <td>{{ optional($item->company)->name }}</td>
                        <td class="text-center">
                            @if ($item->current_stock > 0)
                                <a target="_blank" href="{{ route('item_details') }}?&company_id={{ $item->company_id }}&item_id={{ $item->name }}&from_date={{ request('from_date') }}&to_date={{ request('to_date') }}">{{ $item->current_stock }}</a>
                            @else
                                0
                            @endif

                        </td>
                    </tr>
                @endforeach
                @if (count($item_stocks) == 0)
                    <tr>
                        <td colspan="6" class="text-center">
                            <b class="text-danger">No records found!</b>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if (isset($item_stocks))
            @if (count($item_stocks) != 0)
                @include('partials._paginate', ['data' => $item_stocks])

                <div class="pull-left" style="margin-top:10px; margin-left:10px">
                <span onclick="exportData('{{ url('generalstore/export-gs-as-excel') }}')" style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                </span>
                    <span onclick="exportData('{{ url('generalstore/export-gs-as-pdf') }}')" style="margin-right: 5px; cursor: pointer;">
                    <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                </span>
                </div>

                <form class="exportForm" method="POST">
                    @csrf
                    <input type="hidden" name="model" value="Item Ledger">
                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                </form>
            @endif
        @endif

    </div>
</div>
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('js')

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />


<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>


<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

<script type="text/javascript">
    function exportData(url)
    {
        $('.exportForm').attr('action', url).submit();
    }
</script>


<!--  Select Box Search-->
<script type="text/javascript">

    const comppanyId        = $('#company_id')
    const itemId            = $('#item_id')
    const itemStockRoute    = `{{ route('company-items') }}`


    // data picker
    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format:'yyyy-mm-dd',
    });


    function loadCompanyItems()
    {
        let company_id = comppanyId.val()

        $.get(itemStockRoute, { company_id: company_id }, function(res) {
            itemId.empty()
            itemId.append('<option value="">-Select-</option>')
            res.forEach(function(item) {
                itemId.append('<option value="' + item.id + '">' + item.name + '</option>')
            })

            itemId.trigger('chosen:updated')
        });
    }


</script>


@stop
