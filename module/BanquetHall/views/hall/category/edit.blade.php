@extends('layouts.master')
@section('title', 'Edit Category')
@section('page-header')
    <i class="fa fa-edit"></i> Edit Category
@stop
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
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    @if (hasPermission('banquet.hall-categories.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('banquet.hall-categories.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> List
                            </a>
                        </span>
                    @endif

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('banquet.hall-categories.update', $category->id) }}"
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
                                    <button class="btn btn-xs btn-success" type="submit"> <i class="fa fa-save"></i>
                                        Save</button>
                                    <button class="btn btn-xs btn-gray" type="Reset"> <i class="fa fa-refresh"></i>
                                        Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection



@section('js')
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>

    @include('category.inc.script')

@endsection
