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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-sales" title="Sale list" description="Bar invoices with payment status.">
    <x-slot name="actions">
        @if (hasPermission('bar.sales.create', $slugs))
                <a class="mm-button" href="{{ route('bar.sales.create') }}">
                    <i class="fa fa-plus"></i> Add New
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <!-- Search -->
        <div class="row">
            <div class="col-sm-9 col-sm-offset-2">
                <form>
                    <x-mm.table-scroll label="Sales">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">Customer</span>
                                            <input type="text" class="form-control" name="customer"
                                                placeholder="Type customer name...">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">Date</span>
                                            <input type="text" name="date" value="{{ request('date') }}"
                                                class="form-control date-picker" placeholder="Date">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-addon">Invoice</span>
                                            <input type="text" name="invoice_no"
                                                value="{{ request('invoice_no') }}" class="form-control"
                                                placeholder="Invoice No">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-corner">
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                            <a href="{{ request()->url() }}" class="btn btn-sm">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </x-mm.table-scroll>

                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 px-2">
                <x-mm.table-scroll label="Sales">
                    <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="1%">Sl</th>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-right">Discount</th>
                                <th class="text-right">Paid</th>
                                <th class="text-right">Due</th>
                                <th width="10%" class="text-center">Action</th>
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
                                        {{ $sale->guest_name ?? optional($sale->guestInfo)->name }}
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
                                        {{-- {{ number_format($sale->due_amount, 2) }} --}}
                                        {{ $sale->payable_amount > $sale->paid_amount ? number_format($sale->payable_amount - $sale->paid_amount, 2) : '0' }}
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @if (auth()->id() == 1)
                                                <form
                                                    action="{{ route('bar.update-sale-subtotal-with-transaction', $sale->id) }}"
                                                    method="POST" style="display: inline-block">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-xs">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
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
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
