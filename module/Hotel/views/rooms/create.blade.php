@extends('layouts.master')

@section('title', 'Room Manage')

@section('page-header')
    <i class="fa fa-gears"></i> Room Manage
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <div class="page-header">
        <h1>
            <i class="fa fa-info-circle"></i> Create Room
        </h1>
    </div>

    <x-alert-message />



    <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-8">
            <div class="col-sm-12 render-class">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title"><i class="fa fa-plus-circle"></i> Add New Room </h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            @php
                                setting('room_wise_pricing_booking');
                            @endphp
                            <div style="margin: 20px;">

                            </div>

                            <form class="form-horizontal roomCreateForm" id="companyForm" action="{{ route('rooms.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="roomId" value="null">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Category Name <span
                                                    style="color: deeppink">*</span> </label>

                                            <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                                {!! Form::select('name', $room_category, null, [
                                                    'class' => 'form-control chosen-select',
                                                    'id' => 'category',
                                                    'placeholder' => 'Select Room Name',
                                                    'onchange' => 'checkRoomNumberByCategory(this)',
                                                    'required',
                                                ]) !!}

                                                @error('name')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> LOT </label>
                                            <div class="col-xs-12 col-sm-8 @error('lot') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="lot" id="lot" placeholder="Enter LOT">
                                                @error('lot')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Room Number <span
                                                    style="color: deeppink">*</span></label>
                                            <div class="col-xs-12 col-sm-8 @error('room_number') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="room_number" id="roomNumber"
                                                    value="{{ old('room_number') }}" placeholder="Enter Room Number" required
                                                    onkeyup="checkRoomNumber(this)">
                                                <p id="duplicateRoomError" style="color: red; display: none;"></p>
                                                @error('room_number')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> F R ID Card </label>
                                            <div class="col-xs-12 col-sm-8 @error('f_r_id_card') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="f_r_id_card"
                                                    value="{{ old('f_r_id_card') }}" placeholder="Enter F R ID Card">

                                                @error('f_r_id_card')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if (setting('room_wise_pricing_booking') == 1)
                                        {{-- Rent  --}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="form-field-1-1"> Rent/Night </label>
                                                <div class="col-xs-12 col-sm-8 @error('rent') has-error @enderror">
                                                    <input type="text" class="form-control input-sm" name="rent"
                                                        value="{{ old('rent') }}" placeholder="Rent/Night">

                                                    @error('rent')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Beds  --}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="form-field-1-1"> Beds </label>
                                                <div class="col-xs-12 col-sm-8 @error('beds') has-error @enderror">
                                                    <input type="text" class="form-control input-sm" name="beds"
                                                        value="{{ old('beds') }}" placeholder="Enter Beds">

                                                    @error('beds')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Max Guests  --}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="form-field-1-1"> Max Guests </label>
                                                <div class="col-xs-12 col-sm-8 @error('max_guests') has-error @enderror">
                                                    <input type="text" class="form-control input-sm" name="max_guests"
                                                        value="{{ old('max_guests') }}" placeholder="Enter Max Guests">

                                                    @error('max_guests')
                                                        <span class="text-danger"> {{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Room Size </label>
                                            <div class="col-xs-12 col-sm-8 @error('room_size') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="room_size" id="room_size" placeholder="Enter Room Size">
                                                @error('room_size')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Room Price </label>
                                            <div class="col-xs-12 col-sm-8 @error('room_price') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="room_price" id="room_price" placeholder="Enter Room Size">
                                                @error('room_price')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1">Bed per Room </label>
                                            <div class="col-xs-12 col-sm-8 @error('bed_per_room') has-error @enderror">
                                                <input type="text" class="form-control input-sm" name="bed_per_room" id="bed_per_room" placeholder="Enter Bed per Room">
                                                @error('bed_per_room')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1">Status</label>
                                            <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                                <select name="status" class="form-control chosen-select" required>
                                                    <option></option>
                                                    <option value="1" selected>Ready</option>
                                                    <option value="0">Dirty</option>
                                                    <option value="2">Maintenance</option>
                                                </select>
                                                @error('status')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1">Smoking Status</label>
                                            <div class="col-xs-12 col-sm-8 @error('smoking_status') has-error @enderror">
                                                <select name="smoking_status" class="form-control chosen-select" required>
                                                    <option value="No" selected>No</option>
                                                    <option value="Yes">Yes</option>
                                                </select>
                                                @error('smoking_status')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1">Is Breakfast</label>
                                            <div class="col-xs-12 col-sm-8 @error('is_breakfast') has-error @enderror">
                                                <select name="is_breakfast" class="form-control chosen-select">
                                                    <option value="0" selected>No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                                @error('is_breakfast')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-sm-12" style="display: none">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1">Date :</label>
                                            <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                                <div class="input-group">
                                                    <div class="input-group-addon"><span class="fa fa-calendar"></span>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm date-picker"
                                                            name="from_date" value="{{ request('from_date', date('Y-m-d')) }}"
                                                            autocomplete="off">
                                                        <span class="input-group-addon">From|To</span>
                                                        <input type="text" class="form-control input-sm date-picker"
                                                            name="to_date" value="{{ request('to_date', date('Y-m-d')) }}"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <div class="form-actions center" style="text-align: right !important;">
                                    <button type="submit" class="btn-sm btn-outline-success" id="submitRoomFormBtn"
                                        onclick="submitRoomStoreForm(this)">
                                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                        Save
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xs-2"></div>

    </div>
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>


    @include('rooms.inc.script')


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>
@stop
