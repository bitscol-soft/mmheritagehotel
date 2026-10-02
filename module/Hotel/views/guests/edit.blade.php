
@extends('layouts.master')
@section('title','Edit Guest')
@section('page-header')
    <i class="fa fa-gears"></i> Edit Guest User
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
		<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-guest-form" title="Edit guest" description="Manage guest details, contact information and identity documents.">
        <x-slot name="actions">
            <a href="{{ route('guests.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Guest directory
            </a>
        </x-slot>
        <x-mm.panel>
        @include('partials._alert_message')
                        <form class="form-horizontal" id="companyForm" action="{{ route('guests.update',$guests->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">

                                    <hr>
                                    <div class="form-group">
                                        <label for="company_id" class="col-sm-3 control-label">Company</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <select name="company_id" class="form-control chosen-select" id="company_id" data-selected="{{ $guests->company_id }}" data-placeholder="--Choose Company--">
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->org_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="guest-guest-name" class="col-sm-3 control-label">Guest Name<sup class="text-danger">*</sup></label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-guest-name" type="text" class="form-control input-sm" name="guest_name"
                                                   value="{{ $guests->name }}" placeholder="Guest Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-phone-no" class="col-sm-3 control-label">Phone No </label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-phone-no" type="text" inputmode="tel" class="form-control input-sm" name="phone_no"
                                                   value="{{ $guests->phone_no }}" placeholder="Enter Phone no">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-email" class="col-sm-3 control-label">Email </label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-email" type="text" class="form-control input-sm" name="email"
                                                   value="{{ $guests->email }}" placeholder="Enter Email">

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-gender" class="col-sm-3 control-label">Gender</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <select id="guest-gender" name="gender" class="form-control" data-selected="{{ $guests->gender }}">
                                                <option value="">Select Gender</option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                                <option value="0">Others</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-age" class="col-sm-3 control-label">Age</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-age" type="text" class="form-control input-sm" name="age" value="{{ $guests->age }}" placeholder="Age">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-profession" class="col-sm-3 control-label">Profession</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-profession" type="text" class="form-control input-sm" name="profession" value="{{ $guests->profession }}" placeholder="Profession Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-father-name" class="col-sm-3 control-label">Father's Name</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-father-name" type="text" class="form-control input-sm" name="father_name" value="{{ $guests->father_name }}" placeholder="Father's Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-nid-no" class="col-sm-3 control-label">NID / Passport Number</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-nid-no" type="text" class="form-control input-sm" name="nid_no"
                                                   value="{{ $guests->nid_no }}" placeholder="Enter NID or Passport Number">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-passport-expiry-date" class="col-sm-3 control-label">Passport Expiry Date</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-passport-expiry-date" type="text" class="form-control input-sm date-picker pointer" name="passport_expiry_date"
                                                   value="{{ old('passport_expiry_date', $guests->passport_expiry_date) }}" placeholder="Enter Passport expire date">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-spouse-name" class="col-sm-3 control-label">Spouse Name (Optional)</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-spouse-name" type="text" class="form-control input-sm" name="spouse_name"
                                                   value="{{ $guests->spouse_name }}" placeholder="Enter Spouse Name (Optional)">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="country_id" class="col-sm-3 control-label add_asterisk">Country</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <select name="country_id" class="form-control" id="country_id" data-selected="{{ $guests->country_id ?? 18 }}">
                                                @foreach($countries as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-city-id" class="col-sm-3 control-label">City</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input id="guest-city-id" type="text" class="form-control input-sm" name="city_id" value="{{ $guests->city_id }}" placeholder="Enter City Name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="guest-address" class="col-sm-3 control-label">Address</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <textarea id="guest-address" type="text" class="form-control input-sm" name="address"
                                                placeholder="Enter guest address">{{ $guests->address }}</textarea>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-reference" class="col-sm-3 control-label">Reference Name</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <textarea id="guest-reference" type="text" class="form-control input-sm" name="reference"
                                                placeholder="Reference Name">{{ $guests->reference }}</textarea>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-image" class="col-sm-3 control-label">Image</label>
                                        <div class="col-xs-6 col-sm-4 image-section" style="position: relative">

                                            <input id="guest-image" type="file" name="image" class="image">

                                            @include('guests.include.webcam-modal')
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary btn-sm webcam-modal-btn" onclick="configure()" style="position: absolute;top:1px;right:14px;border: none;" data-toggle="modal" aria-label="Take guest photo with webcam" data-target="#webcam-modal">
                                                <i class="fa fa-camera"></i>
                                            </button>
                                            <input type="hidden" name="web_cam" value="0" class="is_web_cam_or_not">
                                            <input type="hidden" name="image" class="image-tag">
                                        </div>
                                        <div class="col-xs-6 col-sm-4 image-section"  style="position: relative">
                                            <div id="results"></div>
                                            <a href="javascript:void(0)" class="delete-snap" style="display: none;position: absolute;top:0;right:55px"><i class="fa fa-times"></i></a>
                                        </div>
                                        <div class="old-image">
                                            <img height="100" width="100" src="{{ asset($guests->image) }}" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-nid-front" class="col-sm-3 control-label">NID / Passport Photo (Optional)</label>
                                        <div class="col-xs-6 col-sm-4 position-relative upload_nid">

                                            <input id="guest-nid-front" type="file" name="nid_front" class="id-input-file-3">

                                            <div class="nid_photo position-relative">
                                                <img height="200" width="100%" src="{{ asset($guests->nid_front) }}" alt="">
                                                <div class="photo-remove">Remove</div>
                                            </div>
                                        </div>

                                        <div class="col-xs-6 col-sm-4 position-relative upload_nid">
                                            <input type="file" aria-label="NID or passport back" name="nid_back" class="id-input-file-3">

                                            <div class="nid_photo position-relative">
                                                <img height="200" width="100%" src="{{ asset($guests->nid_back) }}" alt="">
                                                <div class="photo-remove">Remove</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="guest-spouse-nid-front" class="col-sm-3 control-label">Spouse NID / Passport Photo (Optional)</label>
                                        <div class="col-xs-6 col-sm-4 position-relative upload_nid">

                                            <input id="guest-spouse-nid-front" type="file" name="spouse_nid_front" class="id-input-file-3">

                                            <div class="nid_photo position-relative">
                                                <img height="200" width="100%" src="{{ asset($guests->spouse_nid_front) }}" alt="">
                                                <div class="photo-remove">Remove</div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-sm-4 position-relative upload_nid">

                                            <input type="file" aria-label="Spouse NID or passport back" name="spouse_nid_back" class="id-input-file-3">

                                            <div class="nid_photo position-relative">
                                                <img height="200" width="100%" src="{{ asset($guests->spouse_nid_back) }}" alt="">
                                                <div class="photo-remove">Remove</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="mm-button">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>

                                <a href="{{ route('guests.index') }}" class="mm-button mm-button-secondary">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
                            </div>
                        </form>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
  <!--Drag and drop-->
  <script type="text/javascript">
    jQuery(function($) {
        $('.id-input-file-3').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport Photo',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
        $('.image').ace_file_input({
            style: 'well',
            btn_choose: 'Image upload',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
<script>
    $('.photo-remove').click(function(){
        $(this).closest('.nid_photo').remove();
        $(this).closest('.ace-file-input').show();

    });
</script>




<script src="{{ asset('assets/js/webcam.min.js') }}"></script>

<script language="JavaScript">
    function configure(){
        Webcam.set({
            width: 446,
            height: 326,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
    }

    function reset(){
        Webcam.reset();
    }

    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            // document.querySelector('.image-tag').setAttribute('src', "data:image/jpg;base64," + data_uri);
            document.getElementById('results').innerHTML = '<img widht="150" height="120" src="'+data_uri+'"/>';
            $('.image-tag').val(data_uri);
            $('.is_web_cam_or_not').val(1);
            $('.image').prop('disabled', true);
            $('.old-image').hide();

            Webcam.reset();
            document.getElementById('my_camera').innerHTML = '<img src="'+data_uri+'"/>';
            $('.delete-snap').show();
        } );
    }

    $('.delete-snap').on('click', function(){
        $('#results').empty();
        $('.delete-snap').hide();
        $('.image').prop('disabled', false);
    })

</script>


@endsection
