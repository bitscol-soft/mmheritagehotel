@extends('layouts.master')
@section('title', 'Invoice No: ' . $stockAdjustment->invoice_no)

@section('content')

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-inv mm-rst-form mm-rst-adjust" title="Stock adjustment" description="Review the adjusted products and quantities.">
    <x-mm.panel class="tw-p-4">
        <div style="text-align:center;" class="row">
                        <h3>Stock Adjustment</h3>
                        <p>Invoice :{{ $stockAdjustment->invoice_no }} </p>
                    </div>

        @include('partials._alert_message')

        <div style="" class="row">
            <div class="col-lg-6">
                <h4>Supplier : {{ $stockAdjustment->supplier_id ?? '' }}</h4>
                <p>Date : {{ $stockAdjustment->date }}</p>
                <p>Approved Date : {{ $stockAdjustment->approve_date }}</p>

            </div>

            <div class="btn-group" style="float: right">
                <a class="btn btn-sm btn-info btn-outline-warning"> <i class="fa fa-check-circle"></i>
                    {{ $stockAdjustment->current_status }} </a>
            </div>
            {{-- <div class="btn-group" style="float: right">
                <a class="btn btn-sm btn-info" href="{{ route('rst.stock-adjustment.index') }}"> <i
                        class="fa fa-clock-o"></i> Pending </a>
            </div> --}}

        </div>

        <!-- PURCHASE TABLE -->
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <x-mm.table-scroll label="Stock adjustment items">
                        <table class="table table-bordered" id="purchaseTable">
                            <thead>
                                <tr class="table-header-bg">
                                    <th width="25%">Product</th>
                                    <th width="10%">Adjust Quantity</th>
                                    <th width="10%">Adjust Reason</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($Adjustment->adjustment_details as $adjustmentDetail)
                                    <tr>
                                        <input type="hidden" name="stock_transfer_detail_id[]"
                                            value="{{ $adjustmentDetail->id }}">
                                        <th width="25%">
                                            {{ optional($adjustmentDetail->product)->name }}
                                        </th>

                                        <th width="10%">
                                            {{ number_format($adjustmentDetail->quantity, 2, '.', '') }}
                                        </th>
                                        <th width="10%">
                                            {{ $adjustmentDetail->adjustment_reason }}
                                        </th>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </x-mm.table-scroll>
                </div>
            </div>
        </div>

        <div class="btn-group" style="float: right">
            <a class="btn btn-sm btn-info" href="{{ route('rst.stock-adjustment.index') }}"> <i
                    class="fa fa-bars"></i> LIST </a>
        </div>

    </x-mm.panel>
</x-mm.page>
@endsection

@section('script')
@endsection
