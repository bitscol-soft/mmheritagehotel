@extends('layouts.master')


@section('title', 'Restaurant New Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('rst/sales-v2/_inc/style')

@endsection
@section('content')

    <x-mm.styles />
    <x-widget.guest-create-modal />

    <x-mm.page class="mm-rst mm-rst-pos mm-rst-sale" title="Restaurant POS" description="Select guest, room or table, add dishes, fire order or collect payment.">
        <x-slot name="actions">
            <a href="{{ route('rst.table-manages.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-th-large" aria-hidden="true"></i> Tables
            </a>
            <a href="{{ route('kit.kitchen.create') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-fire" aria-hidden="true"></i> Kitchen Board
            </a>
            <a href="{{ route('rst.sales.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Sale List
            </a>
        </x-slot>

        <x-alert-message />

        <x-mm.stepper :steps="[
            ['key' => 'courses', 'label' => '1. Guest, Room & Table'],
            ['key' => 'fire', 'label' => '2. Dishes & Courses'],
            ['key' => 'pay', 'label' => '3. Bill & Payment'],
        ]" current="courses" />

        <x-mm.panel class="mm-pos-workspace tw-p-4">
            <div class="row mm-pos-layout">
                {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                <input type="hidden" value="{{ route('rst.sales-v2.store') }}" id="storeSaleUrl">

                @include('rst/sales-v2/_inc/left-side')

                @include('rst/sales-v2/_inc/right-side')
            </div>
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')

    {{-- <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script> --}}


    @include('rst/sales-v2/_inc/script')

    @include('sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            var el = document.getElementById("current-time");
            if (el) el.innerHTML = d.toLocaleTimeString();
        }
    </script>

@endsection
