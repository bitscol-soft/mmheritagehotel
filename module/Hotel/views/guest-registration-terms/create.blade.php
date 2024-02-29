
@extends('layouts.master')
@section('title','Add New Guest')
@section('page-header')
    <i class="fad fa-plus-circle"></i> Add New Guest
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
		<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">

                            <a href="{{ route('guests.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> List
                            </a>

                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('guests.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12">

                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Name<sup class="text-danger">*</sup></label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="guest_name"
                                                   value="{{ old('name') }}" placeholder="Guest Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Phone No </label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="number" class="form-control input-sm" name="phone_no"
                                                   value="{{ old('phone_no') }}" placeholder="Enter Phone no">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Email </label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="email"
                                                   value="{{ old('email') }}" placeholder="Enter Email">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Gender</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <select name="gender" class="form-control">
                                                <option value="">Select Gender</option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                                <option value="0">Others</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">NID / Passport Number</label>

                                        <div class="col-xs-12 col-sm-8 @error('nid_no') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="nid_no"
                                                   value="{{ old('nid_no') }}" placeholder="Enter NID or Passport Number">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Passport Expiry Date</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm date-picker pointer" name="passport_expiry_date"
                                                   value="{{ old('passport_expiry_date') }}" placeholder="Enter Passport expire date">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Spouse Name (Optional)</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="spouse_name"
                                                   value="{{ old('spouse_name') }}" placeholder="Enter Spouse Name (Optional)">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label add_asterisk">Country</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <select name="country_id" class="form-control" id="country_id">
                                                @foreach($countries as $id => $name)
                                                <option value="{{ $id }}" {{ $id == 18 ? 'selected':'' }}>{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Address</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <textarea type="text" class="form-control input-sm" name="address"
                                                placeholder="Enter guest address">{{ old('address') }}</textarea>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">NID / Passport Photo (Optional)</label>
                                        <div class="col-xs-6 col-sm-4">
                                            <input type="file" name="nid_front" class="nid-front">
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <input type="file" name="nid_back" class="nid-back">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Spouse NID / Passport Photo (Optional)</label>
                                        <div class="col-xs-6 col-sm-4">
                                            <input type="file" name="spouse_nid_front" class="nid-front">
                                        </div>
                                        <div class="col-xs-6 col-sm-4">
                                            <input type="file" name="spouse_nid_back" class="nid-back">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                                <a href="{{ route('company.index') }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('js')
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
  <!--Drag and drop-->
  <script type="text/javascript">
    jQuery(function($) {
        $('.nid-front').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport (Front Side)',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });

        $('.nid-back').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport (Back Side)',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
@endsection
