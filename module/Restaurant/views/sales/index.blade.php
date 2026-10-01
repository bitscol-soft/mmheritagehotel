@extends('layouts.master')
@section('title', 'Sale List')

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
<x-mm.page class="mm-report mm-rst mm-rst-sales" title="Sale list" description="Restaurant invoices with payment status.">
    @if (hasPermission('pharmacy.view', $slugs))
        <x-slot name="actions">
            <a href="{{ route('rst.sales-v2.create') }}" class="mm-button">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New
            </a>
        </x-slot>
    @endif

    @include('partials._alert_message')

    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">
            <div class="input-group">
                <span class="input-group-addon">Customer</span>
                <input type="text" class="form-control" name="customer"
                    placeholder="Type customer name...">
            </div>

            <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="text" name="date" value="{{ request('date') }}"
                    class="form-control date-picker" placeholder="Date" autocomplete="off">
            </div>

            <div class="input-group">
                <span class="input-group-addon">Invoice</span>
                <input type="text" name="invoice_no"
                    value="{{ request('invoice_no') }}" class="form-control"
                    placeholder="Invoice No">
            </div>

            <div class="btn-group" style="display: flex">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel>
        <x-mm.table-scroll label="Sales">
            <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                <thead>
                    <tr>
                        <th width="1%">Sl</th>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Table No</th>
                        <th class="text-right">Total Amount</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Paid</th>
                        <th class="text-right">Due</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($sales as $sale)
                        <tr class="odd gradeX">
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $sale->date }}
                            </td>
                            <td>
                                {{ $sale->invoice_no }}
                            </td>
                            <td>
                                {{ optional($sale->guestInfo)->name ?? $sale->guest_name }}
                            </td>
                            <td class="text-center">
                                <span class="label label-xs today-checkout">
                                    {{ optional($sale->table)->table_no ?? "N/A" }}
                                </span>
                            </td>
                            <td class="text-right">
                                {{ number_format($sale->payable_amount, 2) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($sale->discount, 2) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($sale->paid_amount, 2) }}
                            </td>
                            <td class="text-right">
                                @if (setting('use_vat_included') == 1)
                                    {{ $sale->subtotal > $sale->paid_amount ? number_format(( $sale->subtotal - $sale->paid_amount ), 2) : '0' }}
                                @else
                                    {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : '0' }}
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group btn-corner">
                                    {{-- <a href="{{ route('bar.sales.create', $sale->id) }}"
                                        class="btn btn-xs btn-inverse">
                                        <i class="fa fa-money"></i>
                                    </a> --}}
                                    @if (hasPermission('resturant.purchases.view', $slugs))
                                        <a href="{{ route('rst.sales.show', $sale->id) }}"
                                            class="btn btn-xs btn-outline-success">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('rst.sales-v2.show', $sale->id) }}?invoice_type=pos"
                                            target="_blank" class="btn btn-xs btn-outline-info">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        {{-- <a href="{{ route('rst.office.copy', $sale->id) }}?invoice_type=pos"
                                            target="_blank" class="btn btn-xs btn-outline-warning">
                                            <i class="fa fa-print" title="Office Copy"></i>
                                        </a> --}}
                                    @endif
                                    @if (hasPermission('resturant.sales.delete', $slugs))
                                        <a href="#"
                                            onclick="delete_item(`{{ route('rst.sales.destroy', $sale->id) }}`)"
                                            class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    @endif
                                </div>

                            </td>

                        </tr>
                    @empty
                        <x-no-table-record />
                    @endforelse
                </tbody>
            </table>
        </x-mm.table-scroll>
        <x-paginate :data="$sales" />
    </x-mm.panel>
</x-mm.page>


@endsection

@section('js')
@endsection
