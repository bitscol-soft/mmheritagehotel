@extends('layouts.master')

@section('title', 'Contra Voucher Details')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        p>span {
            width: 130px;
            display: inline-block;
            font-weight: bold
        }

        p {
            margin-bottom: 0 !important;
        }

    </style>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-invoice-page mm-acc mm-rst mm-rst-inv" title="Contra Voucher Details" description="Contra voucher details and approval.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('voucher-contras.index') }}"><i class="fa fa-list-alt"></i> Refresh Data</a>
        <a class="mm-button" href="{{ route('voucher-contras.create') }}"><i class="fa fa-plus"></i> Create New</a>
    </x-slot>
    <x-mm.print-sheet>
        <x-mm.panel class="tw-p-4">
            @include('partials._alert_message')
            <div class="row px-2 pb-3">
                <div class="col-md-6">
                    <p><span style="width: 100px">Voucher Type</span> {{ $voucher->voucher_type }}</p>
                    <p><span style="width: 100px">Description</span> {{ $voucher->description }}</p>
                </div>
                <div class="col-md-6">
                    <div style="width: 255px; float: right">
                        <p><span style="width: 85px">Invoice No</span> {{ $voucher->invoice_no }}</p>
                        <p><span style="width: 85px">Date</span> {{ $voucher->date }}</p>
                    </div>
                </div>
            </div>

            <!-- LIST -->
            <div class="row" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12">
                    <x-mm.table-scroll label="Contra Voucher Details">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <!-- <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                                <th class="pl-3" style="color: white !important;" width="60%">Account Name</th>
                                                <th class="pl-3" style="color: white !important;" width="20%">Balance Type</th>
                                                <th class="pr-3 text-right" style="color: white !important;">Amount</th> -->
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" width="60%">Account Name
                                    </th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Debit</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Credit</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($voucher->details as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ optional($item->account)->name }}</td>
                                        <td class="pl-3 text-right">
                                            @if ($item->balance_type == 'Debit')
                                                {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                        <td class="pl-3 text-right">
                                            @if ($item->balance_type == 'Credit')
                                                {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="2"><strong class="pull-right">Total</strong></th>
                                    <th class="pl-3 text-right">{{ number_format($voucher->amount, 2) }}</th>
                                    <th class="pl-3 text-right">{{ number_format($voucher->amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </x-mm.table-scroll>
                </div>
            </div>

            @if (request()->routeIs('contra.approve.show') && !$voucher->is_approved)
                <div class="row" style="width: 100%; margin: 0 !important; padding-bottom: 15px">
                    <div class="col-md-12 text-right">
                        <form action="{{ route('contra.approve.update', $voucher->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-circle"></i>
                                Approve</button>
                        </form>
                    </div>
                </div>
            @endif
        </x-mm.panel>
    </x-mm.print-sheet>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

@endsection
