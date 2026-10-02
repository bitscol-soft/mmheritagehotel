@extends('layouts.master')

@section('title', 'Purchase List')

@section('page-header')
    <i class="fa fa-info-circle"></i> Purchase List
@stop


@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Purchase List" description="Purchases with payment status.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('acc-purchases.index') }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button" href="{{ route('acc-purchases.create') }}"><i class="fa fa-plus"></i> Create</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <div class="row">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Purchase List">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Supplier Name</th>
                                <th class="text-right">Total Amount</th>
                                <th class="text-right">Discount Amount</th>
                                <th class="text-right">Paid Amount</th>
                                <th class="text-right">Due Amount</th>
                                <th class="text-center">Source</th>
                                <th class="text-center" width="90px">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $purchase->date }}</td>
                                    <td>{{ $purchase->invoice_no }}</td>
                                    <td>{{ $purchase->supplier->name ?? '' }}</td>
                                    <td class="text-right">{{ $purchase->qty_amount }}</td>
                                    <td class="text-right">{{ $purchase->discount_amount }}</td>
                                    <td class="text-right">{{ $purchase->paid_amount }}</td>
                                    <td class="text-right">{{ $purchase->due_amount }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $purchase->source == 'Production' ? 'badge-inverse' : 'badge-primary' }}">
                                            {{ $purchase->source }}
                                        </span>    
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @include('partials._user-log', ['data' => $purchase, 'icon_size' => 'minier'])
                                            <a href="{{route('acc-purchases.show', $purchase->id)}}" target="_blank" class="btn btn-success btn-minier" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            @if(hasPermission("account-purchases.delete", $slugs) && $purchase->source == 'Account')
                                                <button type="button" onclick="delete_item(`{{ route('acc-purchases.destroy', $purchase->id) }}`)" class="btn btn-minier btn-danger" title="Delete">
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




            @if(count($purchases) <= 0)
                <div class="text-center">
                    <span class="text-warning">No Records Founds Yet!</span>
                </div>
                <br>
            @else
                @include('partials._paginate', ['data' => $purchases])
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
