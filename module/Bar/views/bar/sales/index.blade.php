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
                <a class="mm-button" href="{{ route('bar.sales-v2.create') }}">
                    <i class="fa fa-plus"></i> Add New
                </a>
        @endif
    </x-slot>
    <x-alert-message />
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
                    class="form-control date-picker" placeholder="Date">
            </div>

            <div class="input-group">
                <span class="input-group-addon">Invoice</span>
                <input type="text" name="invoice_no"
                    value="{{ request('invoice_no') }}" class="form-control"
                    placeholder="Invoice No">
            </div>

            <div class="btn-group">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">

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
                                            {{-- <a href="{{ route('bar.sales.create', $sale->id) }}"
                                                class="btn btn-xs btn-inverse">
                                                <i class="fa fa-money"></i>
                                            </a> --}}
                                            @if (hasPermission('bar.sales.view', $slugs))
                                                <a href="{{ route('bar.sales.show', $sale->id) }}"
                                                    class="btn btn-xs btn-outline-success">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('bar.sales-v2.show', $sale->id) }}?invoice_type=pos"
                                                    target="_blank" class="btn btn-xs btn-outline-info">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            @endif
                                            @if (hasPermission('bar.sales.delete', $slugs))
                                                <a href="#"
                                                    onclick="delete_item(`{{ route('bar.sales.destroy', $sale->id) }}`)"
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
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
