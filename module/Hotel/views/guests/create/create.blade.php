<form class="form-horizontal" id="companyForm" action="{{ route('guests.store') }}" method="post" enctype="multipart/form-data" target="_blank">
    @csrf

    <div class="row">
        <div class="col-sm-12">

            <hr>
            <div class="form-group">
                <label class="col-sm-3 control-label add_asterisk">Company</label>

                <div class="col-xs-12 col-sm-8">
                    <select name="company_id" class="form-control chosen-select" id="company_id">
                        <option value=""></option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->org_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Guest Name<sup class="text-danger">*</sup></label>

                <div class="col-xs-12 col-sm-8">
                    <input type="text" class="form-control input-sm" name="guest_name"
                           value="{{ old('guest_name') }}" placeholder="Guest Name" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Phone No<sup class="text-danger">*</sup> </label>

                <div class="col-xs-12 col-sm-8">
                    <input type="text" inputmode="tel" class="form-control input-sm" name="phone_no"
                           value="{{ old('phone_no') }}" placeholder="Enter Phone no" required>
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

                <div class="col-xs-4 col-sm-8">
                    <select name="gender" class="form-control select select2">
                        <option value="">Select Gender</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                        <option value="0">Others</option>
                    </select>

                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Age</label>

                <div class="col-xs-4 col-sm-8">
                    <input type="text" class="form-control input-sm" name="age" value="{{ old('age') }}" placeholder="Age">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Profession</label>

                <div class="col-xs-12 col-sm-8">
                    <input type="text" class="form-control input-sm" name="profession" value="{{ old('profession') }}" placeholder="Profession">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Father's Name</label>

                <div class="col-xs-12 col-sm-8">
                    <input type="text" class="form-control input-sm" name="father_name" value="{{ old('father_name') }}" placeholder="Father's Name">
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
                <label class="col-sm-3 control-label">City</label>

                <div class="col-xs-12 col-sm-8">
                    <input type="text" class="form-control input-sm" name="city_id" value="{{ old('city_id') }}" placeholder="Enter City Name">
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
                <label class="col-sm-3 control-label">Reference Name</label>

                <div class="col-xs-12 col-sm-8">
                    <textarea type="text" class="form-control input-sm" name="reference"
                        placeholder="Reference Name">{{ old('reference') }}</textarea>

                </div>
            </div>

                <!-- Status -->
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        Type :
                    </label>
                    <div class="col-md-2 col-sm-3">
                        <div class="radio">
                            <label>
                                <input type="radio" name="is_stuff"
                                    value="0" id="radio-required" checked>
                                Guest
                            </label>
                        </div>
                    </div>


                    <div class="col-md-2 col-sm-3">
                        <div class="radio">
                            <label>
                                <input type="radio" name="is_stuff"
                                    id="radio-required2" value="1">
                                Stuff
                            </label>
                        </div>
                    </div>

                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Image</label>
                <div class="col-xs-6 col-sm-4 image-section" style="position: relative">

                    <input type="file" name="image" class="image">

                    @include('guests.include.webcam-modal')
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm webcam-modal-btn" onclick="configure()" style="position: absolute;top:1px;right:14px;border: none;" data-toggle="modal" data-target="#webcam-modal">
                        <i class="fa fa-camera"></i>
                    </button>
                    <input type="hidden" name="web_cam" value="0" class="is_web_cam_or_not">
                    <input type="hidden" name="image" class="image-tag">
                </div>
                <div class="col-xs-6 col-sm-4 image-section"  style="position: relative">
                    <div id="results"></div>
                    <a href="javascript:void(0)" class="delete-snap" style="display: none;position: absolute;top:0;right:55px"><i class="fa fa-times"></i></a>
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
        <a href="{{ route('guests.index') }}" class="btn btn-sm btn-info">
            <i class="fa fa-backward"></i> Back List
        </a>
    </div>
</form>
