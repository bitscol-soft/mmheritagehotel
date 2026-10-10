@extends('layouts.master')

@section('title', 'Bar POS')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('bar/sales-v2/_inc/style')
@endsection

@section('content')
    <x-mm.styles />
    @include('bar/sales/_inc/guest-modal')

    <x-mm.page class="mm-bar mm-rst mm-rst-pos mm-rst-sale" title="Bar POS" description="Select guest, room or table, add drinks, fire order or collect payment.">
        <x-slot name="actions">
            <a href="{{ route('bar.table-manages.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-th-large" aria-hidden="true"></i> Tables
            </a>
            <a href="{{ route('bar.sales.index') }}" class="mm-button mm-button-secondary">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Sale List
            </a>
        </x-slot>

        <x-mm.stepper :steps="[
            ['key' => 'courses', 'label' => '1. Guest, Room & Table', 'href' => '#courses'],
            ['key' => 'fire', 'label' => '2. Drinks & Items', 'href' => '#fire'],
            ['key' => 'pay', 'label' => '3. Bill & Payment', 'href' => '#pay'],
        ]" current="courses" />

        <x-mm.panel class="mm-pos-workspace tw-p-4">
            <x-alert-message />

            <div class="row mm-pos-layout">
                {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                <input type="hidden" value="{{ route('bar.sales-v2.store') }}" id="storeSaleUrl">

                @include('bar/sales-v2/_inc/left-side')

                @include('bar/sales-v2/_inc/right-side')
            </div>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    @include('bar/sales-v2/_inc/script')
    @include('bar/sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            document.getElementById("current-time").innerHTML = d.toLocaleTimeString();
        }
    </script>
@endsection
