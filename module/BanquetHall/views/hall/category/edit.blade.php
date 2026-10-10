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
    <x-mm.page class="mm-banquet mm-room-form mm-category-form" title="Edit hall category" description="Update hall type details, rates, amenities and photos.">
        @if (hasPermission('banquet.hall-categories.view', $slugs))
            <x-slot name="actions">
                <a href="{{ route('banquet.hall-categories.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> List
                </a>
            </x-slot>
        @endif
        <x-mm.panel class="tw-p-5">
            <form class="form-horizontal" action="{{ route('banquet.hall-categories.update', $category->id) }}"
                method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('partials._alert_message')

                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <x-mm.field label="Category Name" id="cat_name" name="cat_name" value="{{ $category->name }}" placeholder="Enter Category Name" />
                            </div>
                            <div class="col-md-12">
                                <x-mm.field label="Guest Capacity" id="capacity" name="guest_capacity" type="number" value="{{ $category->guest_capacity }}" placeholder="Enter Guest Capacity" />
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Description</label>
                                    <div class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                        <textarea name="description" rows="5" class="form-control" placeholder="Enter Description">{{ $category->description }}</textarea>

                                        @error('details')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="price">Aminities </label>
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
                                <label class="col-sm-3 control-label">Status</label>

                                <div class="col-xs-12 col-sm-8">
                                    <select name="status" class="form-control chosen-select" required>
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
                    </div>
                </div>

                <div class="form-group">
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
