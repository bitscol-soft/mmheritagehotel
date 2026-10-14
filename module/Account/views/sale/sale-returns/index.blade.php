@extends('layouts.master')

@section('title', 'Sale Return List')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Sale Return List" description="Sale returns.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('acc-sale-returns.index') }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button" href="{{ route('acc-sale-returns.create') }}"><i class="fa fa-plus"></i> Create</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <div class="row">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Sale Return List">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Customer Name</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-right">Paid Amount</th>
                                <th class="text-right">Due Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($saleReturns as $saleReturn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $saleReturn->date }}</td>
                                    <td>{{ $saleReturn->invoice_no }}</td>
                                    <td>{{ $saleReturn->customer->name ?? '' }}</td>
                                    <td class="text-right">{{ $saleReturn->total_payable }}</td>
                                    <td class="text-right">{{ $saleReturn->total_paid_amount }}</td>
                                    <td class="text-right">{{ $saleReturn->total_due_amount }}</td>

                                    <td class="text-center">
                                        <div class="btn-group btn-corner">

                                            @include('partials._user-log', ['data' => $saleReturn])

                                            <a href="{{route('acc-sale-returns.show', $saleReturn->id)}}" target="_blank" class="btn btn-success btn-xs" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            @if(hasPermission("account-sales.delete", $slugs))
                                                <button type="button" onclick="delete_item(`{{ route('acc-sale-returns.destroy', $saleReturn->id) }}`)" class="btn btn-xs btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </div>

            @if(count($saleReturns) <= 0)
                <div class="text-center">
                    <span class="text-warning">No Records Founds Yet!</span>
                </div>
                <br>
            @else
                @include('partials._paginate', ['data' => $saleReturns])
            @endif

        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

@endsection
