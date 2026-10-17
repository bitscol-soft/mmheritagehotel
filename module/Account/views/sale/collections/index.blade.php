@extends('layouts.master')

@section('title', 'Collection')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Collection Lists" description="Customer collections.">
    <x-slot name="actions">
        @if (hasPermission('acc_collections.create', $slugs))
            <a class="mm-button" href="{{ route('acc_collections.create') }}"><i class="fa fa-plus"></i> Add New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        @if ($collections->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th class="text-right">Amount</th>
                            <th>Remarks</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collections as $collection)
                            <tr>
                                <td>{{ $collection->date }}</td>
                                <td>{{ $collection->invoice_no }}</td>
                                <td>{{ $collection->transaction_no }}</td>
                                <td>{{ optional($collection->customer)->name }}</td>
                                <td class="text-right">{{ number_format($collection->amount, 2) }}</td>
                                <td>{{ $collection->remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission('acc_collections.edit', $slugs))
                                            <a href="{{ route('acc_collections.edit', $collection->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif
                                        @if (hasPermission('acc_collections.delete', $slugs))
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="delete_item('{{ route('acc_collections.destroy', $collection->id) }}')">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                <span class="text-warning">No Records Founds Yet!</span>
            </div>
        @endif
        <br>
        <!-- INPUTS -->
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection
