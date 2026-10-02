@extends('layouts.master')
@section('title', 'Product')
@section('page-header')
    <i class="fa fa-plus"></i> Product Create
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Product Create" description="Add a product.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('products.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
                        <!-- INPUTS -->
                        <form action="{{ route('products.store') }}" method="post">
                            @csrf
                            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                                <div class="col-sm-12 px-4">
                                    <!-- Name -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label" for="name">
                                            <b>Name <sup class="text-danger">*</sup></b>
                                        </label>

                                        <div class="col-sm-9">
                                            <input id="name" name="name" type="text" required class="form-control input-sm" placeholder="Name" value="{{ old('name') }}">
                                        </div>
                                    </div>



                                    <!-- DESCRIPTION -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label" for="description">
                                            <b>Description</b>
                                        </label>

                                        <div class="col-sm-9">
                                            <textarea name="description" class="form-control input-sm">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Category -->
                                    <div class="form-group row">
                                        <label class="control-label col-sm-2" for="category_id">
                                            <b>Category<sup class="text-danger"> *</sup></b>
                                        </label>

                                        <div class="col-sm-9">

                                            <select id="category_id" name="category_id" required class="chosen-select-100-percent form-control required"
                                                    data-placeholder="- Select Category -">
                                                <option value=""></option>

                                                @foreach($categories as $id => $name)
                                                    <option value="{{ $id }}" {{ $id == old('category_id') ? 'selected' : '' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Unit -->
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-sm-2" for="category_id">
                                                <b>Unit<sup class="text-danger"> *</sup></b>
                                            </label>
                                            <div class="col-sm-9">
                                                <select id="category_id" name="unit_id" required class="chosen-select-100-percent form-control required"
                                                        data-placeholder="- Select Category -">
                                                    <option value=""></option>
                                                    @foreach($units as $id => $name)
                                                        <option value="{{ $id }}" {{ $id == old('unit_id') ? 'selected' : '' }}>{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- Purchase Price -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label" for="name">
                                            <b>Purchase Price</b>
                                        </label>

                                        <div class="col-sm-9">
                                            <input id="purchase_price" name="purchase_price" onkeypress="return event.charCode >= 46 && event.charCode <= 57" type="text" class="form-control input-sm" placeholder="Product Purchase Price" value="{{ old('purchase_price') }}">
                                        </div>
                                    </div>

                                    <!-- Sale Price -->
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label" for="name">
                                            <b>Sale Price </b>
                                        </label>

                                        <div class="col-sm-9">
                                            <input id="selling_price" name="selling_price" onkeypress="return event.charCode >= 46 && event.charCode <= 57" type="text" class="form-control input-sm" placeholder="Product Sale Price" value="{{ old('selling_price') }}">
                                        </div>
                                    </div>                                    
                                    {{-- <input name="selling_price" type="hidden" class="form-control input-sm" placeholder="Product Sale Price" value="0"> --}}


                                    <!-- Opening Qty -->
        {{--                            <div class="form-group row">--}}
        {{--                                <label class="col-sm-2 control-label" for="name">--}}
        {{--                                    <b>Opening Qty </b>--}}
        {{--                                </label>--}}

        {{--                                <div class="col-sm-9">--}}
        {{--                                    <input id="opening_quantity" name="opening_quantity" onkeypress="return event.charCode >= 46 && event.charCode <= 57" type="text" class="form-control input-sm" placeholder="Opening Quantity" value="{{ old('opening_quantity') }}">--}}
        {{--                                </div>--}}
        {{--                            </div>--}}

                                    <!-- Submit -->
                                    <div class="row">
                                        <div class="col-sm-11">
                                            <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Save</button>
                                        </div>
                                    </div>
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


