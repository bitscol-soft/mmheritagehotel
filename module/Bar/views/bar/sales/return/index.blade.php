@extends('layouts.master')
@section('title', 'Sale Return List')

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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-sales" title="Sale return list" description="Returns raised against bar invoices.">
    <x-slot name="actions">
        @if (hasPermission('pharmacy.view', $slugs))
                <a class="mm-button" href="{{ route('bar.sale-returns.create') }}">
                    <i class="fa fa-plus"></i> Add New
                </a>
        @endif
    </x-slot>
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">
            <input type="text" class="form-control">

            <input type="text" name="invoice_no"
                value="{{ request('invoice_no') }}" class="form-control"
                placeholder="Invoice No">

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
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Sale returns">
                    <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="1%">Sl</th>
                                <th>Invoice ID</th>
                                <th>Guest/Customer</th>
                                <th>Date</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-right">Return Amount</th>
                                <th class="text-right">Due</th>
                                <th width="8%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($sales as $sale)
                                <tr class="odd gradeX">
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $sale->invoice_no }}
                                    </td>
                                    <td>
                                        {{ optional($sale->guest)->name }}
                                    </td>
                                    <td>
                                        {{ $sale->date }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($sale->subtotal, 2) }}
                                    </td>

                                    <td class="text-right">
                                        {{ number_format($sale->return_amount, 2) }}
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($sale->due_amount, 2) }}
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            {{-- <a href="{{ route('bar.sales.create', $sale->id) }}"
                                                class="btn btn-xs btn-inverse">
                                                <i class="fa fa-money"></i>
                                            </a> --}}
                                            <a href="{{ route('bar.sale-returns.show', $sale->id) }}"
                                                class="btn btn-xs btn-success">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="#"
                                                onclick="delete_item(`{{ route('bar.sale-returns.destroy', $sale->id) }}`)"
                                                class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="30" class="text-center">
                                        <strong class="text-danger"
                                            style="font-size: 18px; background:rgba(140, 212, 212, 0.467)">No
                                            Record Found !
                                        </strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </div>
        </div>
        {{ $sales->links() }}
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
