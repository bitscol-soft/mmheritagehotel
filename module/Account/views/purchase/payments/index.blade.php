@extends('layouts.master')
@section('title', 'Supplier')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Payment Lists" description="Supplier payments.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('acc-suppliers.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        {{-- AAAAAAA --}}
        <x-mm.panel class="mm-report-filter">
            <form class="mm-setup-filter mm-report-form" action="" method="get">
                <div class="input-group">
                    <label class="input-group-addon" for="">
                        Invoice No
                    </label>

                    <select name="invoice_no" class="chosen-select-100-percent"
                        data-placeholder="- Select Invoice -" style="display: none;">
                        <option></option>
                        <option value="P-2021-10-000103">P-2021-10-000103</option>
                        <option value="P-2021-10-000104">P-2021-10-000104</option>
                        <option value="P-2021-10-000105">P-2021-10-000105</option>
                        <option value="P-2021-10-000106">P-2021-10-000106</option>
                        <option value="P-2021-10-000107">P-2021-10-000107</option>
                        <option value="P-2021-10-000108">P-2021-10-000108</option>
                        <option value="P-2021-10-000109">P-2021-10-000109</option>
                        <option value="P-2021-10-000110">P-2021-10-000110</option>
                    </select>
                </div>

                <div class="input-group">
                    <label class="input-group-addon" for="">
                        Reference
                    </label>

                    <select name="reference" class="chosen-select-100-percent"
                        data-placeholder="- Select Reference -" style="display: none;">
                        <option></option>
                        <option value="" selected=""></option>
                    </select>
                </div>

                <div class="btn-group btn-corner">
                    <button type="submit" class="mm-button"><i class="fa fa-search"></i>
                        Search</button>
                </div>
            </form>
        </x-mm.panel>
        {{-- AAAAAAA --}}

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <x-mm.table-scroll label="Payment Lists">
                    <table id="data-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- @foreach($purchase as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->date }}</td>
                                <td>{{ $value->invoice_no }}</td>
                                <td>{{ $value->supplier->name }}</td>
                                <td class="text-right">{{ $value->discount_amount }}</td>
                                <td class="text-right">{{ $value->payable_amount }}</td>
                                <td class="text-right">{{ $value->paid_amount }}</td>
                                <td class="text-right">{{ $value->due_amount }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @include('partials._user-log', ['data' => $value])
                                        <a href="{{route('acc_purchases.show', $value->id)}}" target="_blank"
                                            class="btn btn-success btn-xs" title="View Details"><i
                                                class="fa fa-eye"></i></a>
                                        @if(hasPermission("acc_purchases.edit", $slugs))
                                        <a href="{{route('acc_purchases.edit', $value->id)}}" class="btn btn-primary btn-xs"
                                            title="Edit"><i class="fa fa-pencil"></i></a>
                                        @endif
                                        @if(hasPermission("acc_purchases.delete", $slugs))
                                        <a href="#"
                                            onclick="delete_item('{{ route('acc_purchases.destroy', $value->id) }}')"
                                            class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </div>

                                    <!-- delete form -->
                                    <form action="" id="deleteItemForm" method="POST">
                                        @csrf @method("DELETE")
                                    </form>
                                </td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </x-mm.table-scroll>
                {{-- @if(count($purchase) <= 0) --}} <div class="text-center">
                    <span class="text-warning">No Records Founds Yet!</span>
            </div>
            <br>
            {{-- @else --}}
            {{-- @include('partials._paginate', ['data' => $purchase]) --}}
            {{-- @endif --}}

        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection
