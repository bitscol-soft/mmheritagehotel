@extends('layouts.master')


@section('title','Item Ledger')


@section('css')


    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style>
        .bg-header {
            background-color: aliceblue !important;
        }
    </style>
@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Item Ledger" description="Stock movements of one item.">
    @include('partials._alert_message')

    <form class="form-horizontal" action="" method="get">
        <x-mm.panel class="mm-report-filter">
            <div class="mm-setup-filter mm-report-form">
                <div class="input-group">
                    <span class="input-group-addon">Company</span>
                    <select name="company_id" id="company_id" class="form-control chosen-select-100-percent required" required="required">
                        <option selected disabled value="">select</option>

                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request()->company_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="input-group">
                    <input type="text" class="form-control input-sm date-picker" name="from_date" value="{{ request('from_date') }}" autocomplete="off">
                    <span class="input-group-addon">From|To</span>
                    <input type="text" class="form-control input-sm date-picker" name="to_date" value="{{ request('to_date') }}" autocomplete="off">
                </div>

                <div class="input-group">
                    <span class="input-group-addon">Product</span>
                    <select name="product_id" class="form-control item chosen-select-100-percent required" id="products" required="required">
                        <option selected disabled value="">select</option>
                        @if (isset($items))
                            @foreach($items as $id => $item)
                                <option value="{{ $id }}" {{ request()->product_id == $id ? 'selected':'' }}>{{ $item }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="btn-group">
                    <button class="mm-button" type="submit">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </div>
        </x-mm.panel>

        <x-mm.panel class="tw-p-4">
            <h4 class="text-center"><strong>@if(isset($selected_item)) {{ "'".$selected_item->name."' -" }} @endif Stock Details</strong></h4>
            <x-mm.table-scroll label="Item Ledger">
                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-header" rowspan="2" style="width:100px !important">Date</th>
                                            <th class="text-center bg-header" colspan="3">Opening Balance</th>
                                            <th class="text-center bg-header" colspan="4">Stock In</th>
                                            <th class="text-center bg-header" colspan="4">Stock OUt</th>
                                            <th class="text-center bg-header" colspan="3">Closing Balance</th>
                                        </tr>
                                        <tr>
                                            <th class="bg-header text-center">Qty</th>
                                            <th class="bg-header text-right">Rate</th>
                                            <th class="bg-header text-right">Amount</th>
                                            <th class="bg-header" style="width:130px !important;">Invoice No</th>
                                            <th class="bg-header text-center">Qty</th>
                                            <th class="bg-header text-right">Rate</th>
                                            <th class="bg-header text-right">Amount</th>
                                            <th class="bg-header">Invoice No</th>
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
                                                    <td class="text-center">{{ number_format($opening_qty ?? 0, 2)  }}</td>
                                                    <td class="text-right">{{ number_format($opening_cost, 2) }}</td>
                                                    <td class="text-right">{{ round($opening_amount, 2)   }}</td>

                                                    @if ($details->type == "Prod Purchase Receive" || $details->type == "Purchase Receive")
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

                                                    <td>{{ number_format($final_qty ?? 0, 2) }}</td>
                                                    <td class="text-right">{{ number_format($final_rate, 2) }}</td>
                                                    <td class="text-right">{{ number_format($final_amount, 2) }}</td>
                                                </tr>

                                                    <input type="hidden" name="last_qty" value="{{ $final_qty }}">
                                                    <input type="hidden" name="last_cost" value="{{ $opening_cost }}">
                                                    <input type="hidden" name="last_amount" value="{{ $opening_amount }}">

                                                @empty
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($selected_item ? $selected_item->created_at : '')->format('y-m-d') }}</td>
                                                    <td class="text-center">{{ number_format($opening_stock ?? 0, 2) }}</td>
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


                                                    <td class="text-center">{{ number_format($opening_stock ?? 0.00, 2) }}</td>
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
        </x-mm.panel>
    </form>

    <x-mm.panel class="tw-p-4">
        @if (isset($item_stock_details))
            @include('partials._paginate', ['data' => $item_stock_details])

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
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>






    <script type="text/javascript">
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
        }
    </script>


        <script>
            const products = $('#products')

            let counter = 0

            $(document).ready(function() {

                $('#company_id').change(function() {
                    
                    $.get(`/ajax/company-wise-product?company_id=${$(this).val()}`, function(res) {
                        products.empty().append('<option></option>')
                        res.forEach(function(item) {
                                products.append(`<option value="${item.id}">${item.name}</option>`)
                            })

                        products.trigger('chosen:updated');

                        counter++
                    })
                })
            })

            
        </script>
@stop
