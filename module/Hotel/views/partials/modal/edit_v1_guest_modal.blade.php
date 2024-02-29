<div id="edit_guest_info" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(41, 4, 77)">
                <button type="button" class="close white" data-dismiss="modal">&times;</button>
                <h4 class="white bigger"><i class="glyphicon glyphicon-plus "></i>Guest Edit</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="guestEditForm" action="" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="is_ajax" value="1" required>
                    <input type="hidden" name="guest_id" class="edit_guest_id">

                    <div class="row">
                        <div class="col-sm-12">

                            <!-- Guest Name -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name<sup class="text-danger">*</sup></label>
                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="guest_name"
                                        value="{{ old('name') }}" placeholder="Guest Name" required>
                                </div>
                            </div>

                            <!-- Phone No -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Phone No<span class="text-danger">*</span>
                                </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="number" class="form-control input-sm" name="phone_no"
                                        value="{{ old('phone_no') }}" placeholder="Enter Phone no" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="email"
                                        value="{{ old('email') }}" placeholder="Enter Email">
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gender</label>

                                <div class="col-xs-12 col-sm-8 edit_guest_gender">
                                    {{-- <select name="gender" class="form-control chosen-select-100-percent" data-placeholder="--Select Gender--">
                                        <option></option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="0">Others</option>
                                    </select> --}}

                                </div>
                            </div>

                            <!-- NID/PASSPORT -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NID/Passport</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="nid_no"
                                        value="{{ old('nid_no') }}" placeholder="Enter NID or Passport Number">

                                </div>
                            </div>

                            <!-- Age -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Age</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="age"
                                        value="{{ old('age') }}" placeholder="Enter Age">

                                </div>
                            </div>

                            <!-- Profession -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Profession</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="profession"
                                        value="{{ old('profession') }}" placeholder="Enter Profession">

                                </div>
                            </div>

                            <!-- Father's Name -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Father's Name</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="father_name"
                                        value="{{ old('father_name') }}" placeholder="Enter Father's Name">

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Passport Expiry Date</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm date-picker pointer"
                                        name="passport_expiry_date" value="{{ old('passport_expiry_date') }}"
                                        placeholder="Passport Expire Date">

                                </div>
                            </div>

                            <!-- Spouse Name -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Spouse Name
                                </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="spouse_name"
                                        value="{{ old('spouse_name') }}" placeholder="Enter Spouse Name (Optional)">

                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address</label>

                                <div class="col-xs-12 col-sm-8">
                                    <textarea type="text" class="form-control input-sm" name="address" placeholder="Enter guest address">{{ old('address') }}</textarea>

                                </div>
                            </div>

                            <!-- NID/PASSPORT ATTACH BACK/FRONT-->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Image</label>
                                <div class="col-xs-6 col-sm-4 image-section " style="position: relative">
                                    <input type="file" name="image" class="edit_guest_profile_image">
                                    <!-- Button trigger modal -->
                                    <button type="button"
                                        class="btn btn-primary btn-sm webcam-modal-btn editGuestWebCam"
                                        onclick="editGuestWebCam()"
                                        style="position: absolute;top:1px;right:14px;border: none;">
                                        <i class="fa fa-camera"></i>
                                    </button>
                                    <input type="hidden" name="web_cam" value="0"
                                        class="edit_guest_is_web_cam_or_not">
                                    <input type="hidden" name="image" class="edit_guest_image-tag">
                                    <div class="edit_guest_image"></div>
                                </div>
                                <div class="col-xs-6 col-sm-4 image-section" style="position: relative">
                                    <div id="edit_guesr_my_camera"
                                        style="width: 600px !important; height: 135px !important;display: inline;">
                                    </div>
                                    <div style="text-align: center; margin-top: -39px;">
                                        <a href="javascript:void(0)" onclick="edit_guest_take_snapshot()"
                                            class="btn btn-success btn-sm edit_guest_take_snapshot"
                                            style="display: none"><i class="fa fa-camera"></i></a>
                                    </div>

                                    <div id="edit_guest_results"></div>
                                    <a href="javascript:void(0)" class="edit_guest_delete-snap"
                                        style="display: none;position: absolute;top:0;right:55px"><i
                                            class="fa fa-times"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- NID/PASSPORT ATTACH BACK/FRONT-->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                NID/Passport Photo
                            </label>
                            <div class="col-xs-6 col-sm-4 ">
                                <input type="file" name="nid_front" class="nid-back">
                                <div class="guest_nid_front_view"></div>
                            </div>
                            <div class="col-xs-6 col-sm-4 ">
                                <input type="file" name="nid_back" class="nid-back">
                                <div class="guest_nid_back_view"></div>
                            </div>
                        </div>

                        <!-- Spouse NID/PASSPORT ATTACH BACK/FRONT-->
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Spouse NID/Passport Photo
                            </label>
                            <div class="col-xs-6 col-sm-4 ">
                                <input type="file" name="spouse_nid_front" class="nid-back">
                                <div class="guest_spouse_nid_front_view"></div>
                            </div>
                            <div class="col-xs-6 col-sm-4 ">
                                <input type="file" name="spouse_nid_back" class="nid-back">
                                <div class="guest_spouse_nid_back_view"></div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="form-actions center" style="text-align: right !important;">
                <div class="btn-group btn-corner">
                    <button type="button" class="btn btn-sm btn-success" id="edit_guest_submit">
                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        Save
                    </button>
                    <button class="btn btn-sm" data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i>
                        Cancel
                    </button>
                </div>

            </div>
            </form>
        </div>
    </div>
</div>
</div>
@section('js')
    {{-- <script src="{{ asset('assets/js/dropzone.min.js') }}"></script> --}}
    <!--Drag and drop-->
    {{-- <script type="text/javascript">
        jQuery(function($) {
            $('.nid-front').ace_file_input({
                style: 'well',
                btn_choose: 'Upload NID / Passport (Front Side)',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'

            }).on('change', function() {});

            $('.nid-back').ace_file_input({
                style: 'well',
                btn_choose: 'Upload NID / Passport (Back Side)',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'

            }).on('change', function() {});

            $('.image').ace_file_input({
                style: 'well',
                btn_choose: 'Image Upload',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'

            }).on('change', function() {});
        });
    </script> --}}


@endsection
