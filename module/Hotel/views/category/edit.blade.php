@extends('layouts.master')
@section('title', 'Edit Category')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
    <style>
        .checkbox label input[type=checkbox].ace+.lbl {
            margin-bottom: 10px;
        }

        .cat-img-delete {
            position: absolute;
            top: 2px;
            right: 15px;
            background-color: red;
            color: #fff;
            font-size: 12px;
            padding: 3px 5px;
            border-radius: 100%;
            cursor: pointer;
            display: none;
        }

        .cat-img-box {
            position: relative;
        }

        .cat-img-box:hover .cat-img-delete {
            display: block !important;
        }
    </style>
@endpush

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-room-form mm-category-form" title="Edit room category" description="Update room type details, rates, amenities and photos.">
        @if (hasPermission('hotel-categories.view', $slugs))
            <x-slot name="actions">
                <a href="{{ route('hotel-categories.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Room categories
                </a>
            </x-slot>
        @endif
        <x-mm.panel class="tw-p-5">
<form class="form-horizontal category-form" action="{{ route('hotel-categories.update', $category->id) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('partials._alert_message')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="cat_name"> Category Name </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="cat_name" name="cat_name"
                                                        value="{{ $category->name }}" placeholder="Enter Category Name"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="capacity"> Guest Capacity
                                                </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="number" id="capacity" name="guest_capacity"
                                                        value="{{ $category->guest_capacity }}"
                                                        placeholder="Enter Guest Capacity" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="description">Description</label>
                                                <div class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                                    <textarea id="description" name="description" rows="5" class="form-control" placeholder="Enter Description">{{ $category->description }}</textarea>

                                                    @error('description')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="can_sleep">Can Sleep </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="number" id="can_sleep" name="can_sleep"
                                                        placeholder="Ex - 1 person" class="form-control"
                                                        value="{{ $category->can_sleep }}" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="bed_details">Bed Details </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="bed_details" name="bed_details"
                                                        value="{{ $category->bed_details }}" placeholder="EX - Double Bed"
                                                        class="form-control" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="room_size">Room Sqft</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="number" id="room_size" name="room_size"
                                                        value="{{ $category->room_sqft }}" placeholder="EX - 2400sqft"
                                                        class="form-control" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="guest-wise-price">Guest Wise Price</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <div class="input-group">
                                                        <label>
                                                            <input id="guest-wise-price" name="allow_guest_wise_price" value="1"
                                                                class="ace ace-switch ace-switch-6 guest-wise-price"
                                                                type="checkbox"
                                                                {{ $category->allow_guest_wise_price ? 'checked' : '' }}>
                                                            <span class="lbl"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="price"> Default Price <span
                                                        class="currency-sign"></span></label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" name="price" id="price"
                                                        value="{{ calculateCurrencyAmount($category->price, 1) }}"
                                                        placeholder="Enter Price" class="form-control only-number"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="vat"> Vat (%) </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="number" id="vat" name="vat"
                                                        value="{{ $category->vat }}" placeholder="Enter Vat Amount"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" >Aminities </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <div class="checkbox">
                                                        @foreach ($aminities as $item)
                                                            <label>
                                                                <input name="aminities[]" value="{{ $item->id }}"
                                                                    {{ in_array($item->id, $aminities_item) ? 'checked' : '' }}
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
                                            <label class="col-sm-3 control-label" for="category-status">Status</label>

                                            <div class="col-xs-12 col-sm-8">
                                                <select id="category-status" name="status" class="form-control chosen-select" required>
                                                    <option></option>
                                                    <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>
                                                        Active</option>
                                                    <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>
                                                        In Active</option>
                                                </select>

                                                @error('status')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if ($category->roomMultipleImg)
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Current Photos </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <div class="category-images">
                                                        <div class="row">
                                                            @foreach ($category->roomMultipleImg as $item)
                                                                <div class="col-md-4">
                                                                    <div class="cat-img-box">
                                                                        <img class="img-responsive"
                                                                            src="{{ asset('/') }}{{ $item->relative_path ?? '' }}{{ $item->name ?? 'no-image.jpg' }}"
                                                                            alt="">
                                                                        <div class="cat-img-delete"><i
                                                                                class="ace-icon glyphicon glyphicon-remove"></i>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="room_photos">Room Photos </label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="file" id="room_photos" name="room_photos[]" class="category_photos"
                                                    multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 guest-wise-price-div"
                                style="display: {{ $category->allow_guest_wise_price ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <h4 style="font-weight: bolder">Guest Wise Price</h4>
                                    <hr style="border:2px dotted rgb(182, 214, 182)">
                                    <div class="col-xs-12 price-entry">
                                        @for ($i = 0; $i < $category->guest_capacity; $i++)
                                            <div class="col-sm-3 mb-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">For Guest {{ $i + 1 }} <span
                                                            class="currency-sign"></span></span>
                                                    <input type="text" name="guest_prices[]"
                                                        class="form-control only-number"
                                                        value="{{ setting('root_currency') == 141 ? ($category->roomPrices->pluck('price', 'capacity')[$i + 1] ?? 0) / getCurrentCurrencyRate('bdt') : ($category->roomPrices->pluck('price', 'capacity')[$i + 1] ?? 0) * getCurrentCurrencyRate('bdt') }}">
                                                    <input type="hidden" name="guest_capacities[]"
                                                        value="{{ $i + 1 }}">
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div class="form-group category-form-actions">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                                <div class="col-xs-12 col-sm-12 text-right">
                                    <button class="mm-button" type="submit"> <i class="fa fa-save"></i>
                                        Save</button>
                                    <button class="mm-button mm-button-secondary" type="Reset"> <i class="fa fa-refresh"></i>
                                        Reset</button>
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
