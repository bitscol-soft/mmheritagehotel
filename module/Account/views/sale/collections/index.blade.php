@extends('layouts.master')


@section('title', 'Collection')


@section('page-header')
    <i class="fa fa-plus"></i> Collection Lists
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Collection Lists" description="Customer collections.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('acc-suppliers.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <div class="text-center">
            <span class="text-warning">No Records Founds Yet!</span>
        </div>
        <br>
        <!-- INPUTS -->
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection


