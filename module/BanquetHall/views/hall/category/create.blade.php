@extends('layouts.master')
@section('title', 'Add New BanquetHall Category')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
    <style>
        .checkbox label input[type=checkbox].ace+.lbl {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-room-form mm-category-form" title="Add a hall category" description="Define the hall type, capacity, rates, amenities and photos.">
        @if (hasPermission('suppliers.view', $slugs))
            <x-slot name="actions">
                <a href="{{ route('banquet.hall-categories.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Category List
                </a>
            </x-slot>
        @endif
        <x-mm.panel class="tw-p-5">
            <form class="form-horizontal" action="{{ route('banquet.hall-categories.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <x-alert-message />

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="cat_name"> Name <sup
                                            class="text-danger">*</sup> </label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <input type="text" id="cat_name" name="cat_name"
                                            placeholder="Enter Category Name" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="capacity"> Guest Capacity <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <input type="number" id="capacity" name="guest_capacity"
                                            placeholder="Enter Guest Capacity" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                        <textarea name="description" rows="5" class="form-control" placeholder="Enter Description">{{ old('description') }}</textarea>

                                        @error('details')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
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
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"> Default Price<span
                                            class="currency-sign"></span> <sup class="text-danger">*</sup>
                                    </label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <input type="number" name="price" id="price"
                                            placeholder="Enter Price" class="form-control" required>
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="vat"> Vat(%) </label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <input type="number" id="vat" name="vat" value="0"
                                            placeholder="Enter Vat Amount" class="form-control" required>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="price">Aminities <sup
                                            class="text-danger">*</sup></label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <div class="checkbox">
                                            @foreach ($aminities as $item)
                                                <label>
                                                    <input name="aminities[]" value="{{ $item->id }}"
                                                        type="checkbox" class="ace">
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
                                <label class="col-sm-3 control-label">Status <sup
                                        class="text-danger">*</sup></label>

                                <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                    <select name="status" class="chosen-select form-control" required>
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
                        {{-- <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="price"> Room Photos </label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="file" name="room_photos[]" class="category_photos"
                                        multiple>
                                </div>
                            </div>
                        </div> --}}
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

                <div class="form-group">
                    <div class="col-xs-12 col-sm-12 text-right">
                        <div class="btn-group">
                            <button class="mm-button" type="submit"> <i
                                    class="fa fa-save"></i> Save</button>
                            <button class="mm-button mm-button-secondary" type="Reset"> <i
                                    class="fa fa-refresh"></i> Reset</button>
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
