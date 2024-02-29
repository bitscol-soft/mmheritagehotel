@extends('layouts.master')
@section('title', 'Sale Return List')

@section('page-header')
    <i class="fa fa-info-circle"></i> Sale Return List
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
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('bar.sale-returns.create') }}">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('partials._alert_message')

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Patient/Customer</td>
                                                <td>Invoice No</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="invoice_no"
                                                        value="{{ request('invoice_no') }}" class="form-control"
                                                        placeholder="Invoice No">
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

                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 px-4">
                                <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">Sl</th>
                                            <th>Invoice ID</th>
                                            <th>Patient/Customer</th>
                                            <th>Date</th>
                                            <th class="text-right">Total Amount</th>
                                            <th class="text-right">Return Amount</th>
                                            <th class="text-right">Paid</th>
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
                                                    {{ optional($sale->patient)->name }}
                                                </td>
                                                <td>
                                                    {{ $sale->date }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($sale->subtotal, 2) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($sale->discount, 2) }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format($sale->paid_amount, 2) }}
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
                                                        <a href="{{ route('bar.sales.show', $sale->id) }}"
                                                            class="btn btn-xs btn-success">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="#"
                                                            onclick="delete_item(`{{ route('bar.sales.destroy', $sale->id) }}`)"
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
                            </div>
                        </div>
                        {{ $sales->links() }}

                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
