@extends('layouts.master')
@section('title', 'Supplier')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Supplier Edit" description="Update the supplier.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('acc-suppliers.index') }}"><i class="fa fa-list-alt"></i> List</a>
        <a class="mm-button" href="{{ route('acc-suppliers.create') }}"><i class="fa fa-plus"></i> Create</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{ route('acc-suppliers.update', $supplier->id) }}" method="post">
            @csrf @method('put')
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <!-- Name -->
                @include('includes.inputs.input-field', ['name' => 'name', 'value' => $supplier->name, 'is_required' => true])

                <!-- Mobile -->
                @include('includes.inputs.input-field', ['name' => 'mobile', 'value' => $supplier->mobile])

                <!-- Email -->
                @include('includes.inputs.input-field', ['name' => 'email', 'value' => $supplier->email, 'type' => 'email'])

                <!-- Address -->
                @include('includes.inputs.input-field', ['name' => 'address', 'value' => $supplier->address])

                <!-- Opening balance -->
                {{-- @include('includes.inputs.input-field', ['name' => 'opening_balance', 'title' => 'Opening Balance', 'value' => $supplier->opening_balance]) --}}

                <!-- Current balance -->
                {{-- <!-- @include('includes.inputs.input-field', ['name' => 'current_balance', 'title' => 'Current Balance', 'value' => $supplier->current_balance]) --> --}}

                <!-- Submit -->
                    <button class="btn btn-info btn-sm pull-right"><i class="fa fa-edit"></i> Update</button>
                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection

