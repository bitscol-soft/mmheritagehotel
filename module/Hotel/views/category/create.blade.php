@extends('layouts.master')
@section('title','Add New Category')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
<style>
.checkbox label input[type=checkbox].ace+.lbl{
    margin-bottom: 10px;
}
</style>
@endpush

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-room-form mm-category-form" title="Add a room category" description="Define room type, capacity, rates, amenities, photos and optional guest-based pricing.">
        @if (hasPermission('hotel-categories.view', $slugs))
            <x-slot name="actions">
                <a href="{{ route('hotel-categories.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Room categories
                </a>
            </x-slot>
        @endif
        <x-mm.panel class="tw-p-5">
<form class="form-horizontal category-form" action="{{ route('hotel-categories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <x-alert-message />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-mm.field label="Name" id="cat_name" name="cat_name" placeholder="Enter Category Name" required />
                                    </div>
                                    <div class="col-md-12">
                                        <x-mm.field label="Guest Capacity" id="capacity" name="guest_capacity" type="number" placeholder="Enter Guest Capacity" required />
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="description">Description</label>
                                            <div class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                                <textarea id="description" name="description" rows="5" class="form-control" placeholder="Enter Description">{{ old('description') }}</textarea>

                                                @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <x-mm.field label="Can Sleep" id="can_sleep" name="can_sleep" type="number" placeholder="Ex - 1 person" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-mm.field label="Bed Details" id="bed_details" name="bed_details" placeholder="EX - Double Bed" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-mm.field label="Room Sqft" id="room_size" name="room_size" type="number" placeholder="EX - 2400sqft" />
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="guest-wise-price">Guest Wise Price</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <div class="input-group">
                                                    <label>
                                                        <input id="guest-wise-price" name="allow_guest_wise_price" value="1" class="ace ace-switch ace-switch-6 guest-wise-price" type="checkbox">
                                                        <span class="lbl"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="capacity">Room Photos</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="file" id="capacity" name="room_photos[]" class="form-control" multiple>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-mm.field label="Default Price" id="price" name="price" type="number" placeholder="Enter Price" required help="Currency" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-mm.field label="Vat (%)" id="vat" name="vat" type="number" value="0" placeholder="Enter Vat Amount" required />
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" >Aminities <sup class="text-danger">*</sup></label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <div class="checkbox">
                                                    @foreach ($aminities as $item)
                                                        <label>
                                                            <input name="aminities[]" value="{{ $item->id }}" type="checkbox" class="ace">
                                                            <span class="lbl">&nbsp;{{ $item->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="category-status">Status <sup class="text-danger">*</sup></label>

                                        <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                            <select id="category-status" name="status" class="chosen-select form-control" required>
                                                <option></option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">In Active</option>
                                            </select>

                                            @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="room_photos"> Room Photos </label>
                                        <div class="col-xs-12 col-sm-8 ">
                                            <input type="file" id="room_photos" name="room_photos[]" class="category_photos" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 guest-wise-price-div" style="display: none">
                            <div class="form-group">
                                <h4 style="font-weight: bolder">Guest Wise Price</h4>
                                <hr style="border:2px dotted rgb(182, 214, 182)">
                                <div class="col-xs-12 price-entry">

                                </div>
                            </div>
                        </div>

                        <div class="form-group category-form-actions">
                            <div class="col-xs-12 col-sm-12 text-right">
                                <div class="btn-group">
                                    <button class="mm-button" type="submit"> <i class="fa fa-save"></i> Save</button>
                                    <button class="mm-button mm-button-secondary" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
    @include('category.inc.script')
@endsection

