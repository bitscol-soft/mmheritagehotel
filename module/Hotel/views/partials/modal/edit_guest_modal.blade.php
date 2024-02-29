<div id="editHotelGuestModal" class="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header" style="background: rgb(41, 4, 77)">
                <button type="button" class="close white" data-dismiss="modal">&times;</button>
                <h4 class="white bigger"><i class="glyphicon glyphicon-plus "></i> Edit Guest</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" id="guestEditForm" onsubmit="submitGuestFormAxios(this, '{{ route('rooms.update-hotel-guest-info') }}')" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="is_ajax" value="1" required>
                    <input type="hidden" name="id" value="" id="guestId" required>

                    <div class="row">
                        <div class="col-sm-12">


                            <!-- Guest Name -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Name<sup class="text-danger">*</sup></label>
                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="name" id="name"
                                        value="{{ old('name') }}" placeholder="Guest Name" required>
                                </div>
                            </div>


                            <!-- Phone No -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Phone No<sub class="text-danger">*</sub> </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="number" class="form-control input-sm" name="phone_no" id="phone_no"
                                        value="{{ old('phone_no') }}" placeholder="Enter Phone no" required>
                                </div>
                            </div>




                            <!-- Email -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="email" id="email"
                                        value="{{ old('email') }}" placeholder="Enter Email">
                                </div>
                            </div>




                            <!-- Gender -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Gender</label>

                                <div class="col-xs-12 col-sm-8">
                                    <select name="gender" id="editGender" class="form-control select2" data-placeholder="--Select Gender--" style="width: 100%">
                                        <option></option>

                                    </select>
                                </div>
                            </div>



                            <!-- NID/PASSPORT -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NID/Passport</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="nid_no" id="nid_no"
                                        value="{{ old('nid_no') }}" placeholder="Enter NID or Passport Number">

                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label">Passport Expiry Date</label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm date-picker pointer" name="passport_expiry_date" id="passport_expiry_date"
                                        value="{{ old('passport_expiry_date') }}" placeholder="Passport Expire Date">

                                </div>
                            </div>


                            <!-- Spouse Name -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Spouse Name
                                </label>

                                <div class="col-xs-12 col-sm-8">
                                    <input type="text" class="form-control input-sm" name="spouse_name" id="spouse_name"
                                        value="{{ old('spouse_name') }}" placeholder="Enter Spouse Name (Optional)">

                                </div>
                            </div>



                            <!-- Country -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label add_asterisk">Country</label>

                                <div class="col-xs-12 col-sm-8">
                                    <select name="country_id" class="form-control select2" id="country_id" style="width: 100%">
                                        @foreach ($countries as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <!-- Address -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address</label>

                                <div class="col-xs-12 col-sm-8">
                                    <textarea type="text" class="form-control input-sm" name="address" id="address"
                                        placeholder="Enter guest address">{{ old('address') }}</textarea>

                                </div>
                            </div>


                            <!-- OLD NID/PASSPORT ATTACH BACK/FRONT-->
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    NID/Passport Photo
                                </label>
                                <div class="col-xs-6 col-sm-4">
                                    <input type="file" name="nid_front" class="nid-back">
                                </div>
                                <div class="col-xs-6 col-sm-4">
                                    <input type="file" name="nid_back" class="nid-back">
                                </div>
                            </div> -->


                            <!-- NID/PASSPORT ATTACH BACK/FRONT-->
                            {{-- <div class="form-group">
                                <label class="col-sm-3 control-label">NID / Passport Photo</label>
                                <div class="col-xs-6 col-sm-4 position-relative upload_nid">
                                    <input type="file" name="nid_front" class="nid-back">
                                    <div class="nid_photo position-relative">
                                        <img height="200" width="100%" id="nidFrontImg" src="" alt="">
                                        <div class="photo-remove">Remove</div>
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-4 position-relative upload_nid">
                                    <input type="file" name="nid_back" class="nid-back">
                                    <div class="nid_photo position-relative">
                                        <img height="200" width="100%" id="nidBackImg" src="" alt="">
                                        <div class="photo-remove">Remove</div>
                                    </div>
                                </div>
                            </div> --}}





                            <!-- Spouse NID/PASSPORT ATTACH BACK/FRONT-->
                            {{-- <div class="form-group">
                                <label class="col-sm-3 control-label">Spouse NID / Passport Photo</label>
                                <div class="col-xs-6 col-sm-4 position-relative upload_nid">
                                    <input type="file" name="spouse_nid_front" class="nid-back">
                                    <div class="nid_photo position-relative">
                                        <img height="200" width="100%" id="spouseNidFrontImg" src="" alt="">
                                        <div class="photo-remove">Remove</div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-4 position-relative upload_nid">
                                    <input type="file" name="spouse_nid_back" class="nid-back">
                                    <div class="nid_photo position-relative">
                                        <img height="200" width="100%" id="spouseNidBackImg" src="" alt="">
                                        <div class="photo-remove">Remove</div>
                                    </div>
                                </div>
                            </div> --}}


                            <!-- Old Spouse NID/PASSPORT ATTACH BACK/FRONT-->
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label">Spouse NID/Passport Photo</label>
                                <div class="col-xs-6 col-sm-4">
                                    <input type="file" name="spouse_nid_front" class="nid-back">
                                </div>
                                <div class="col-xs-6 col-sm-4">
                                    <input type="file" name="spouse_nid_back" class="nid-back">
                                </div>
                            </div> -->



                        </div>
                    </div>


                    <div class="form-actions center" style="text-align: right !important;">
                        <div class="btn-group btn-corner">
                            <button class="btn btn-sm btn-success" id="save-btn">
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
        });
    </script>
@endsection
