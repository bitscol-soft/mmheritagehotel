@extends('layouts.master')
@section('title', 'Add New BanquetHall Category')
@section('page-header')
    <i class="fad fa-plus-circle"></i> Add New BanquetHall Category
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
    <style>
        .checkbox label input[type=checkbox].ace+.lbl {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    @if (hasPermission('suppliers.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('banquet.hall-categories.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> Category List
                            </a>
                        </span>
                    @endif

                </div>

                <div class="widget-body">
                    <div class="widget-main">
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
                                        <button class="btn-sm btn btn-outline-success" type="submit"> <i
                                                class="fa fa-save"></i> Save</button>
                                        <button class="btn-sm btn btn-default" type="Reset"> <i
                                                class="fa fa-refresh"></i> Reset</button>
                                    </div>
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
