@extends('layouts.master')

@section('title', 'Room Manage')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-room-form" title="Edit hall" description="Update hall details, availability status and capacity.">
        <x-alert-message />

        <x-mm.panel class="render-class">
            @php
                setting('room_wise_pricing_booking');
            @endphp

            <form class="form-horizontal" id="companyForm"
                action="{{ route('banquet.hall-rooms.update', $room->id) }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" id="roomId" value="{{ $room->id }}">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1"> Room Name <span
                                    style="color: deeppink">*</span></label>

                            <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                {{-- W4.2: kept raw. The onkeyup JS hook in the file
                                     binds checkRoomNumber() to this <input>, and the
                                     <input> shares id="roomNumber" with the Hall Number
                                     <input> below — a refactor would need a follow-up. --}}
                                <input type="text" class="form-control input-sm" name="name"
                                    id="roomNumber" value="{{ $room->name }}" required>
                                <p id="duplicateRoomError" style="color: red; display: none;"></p>
                                @error('name')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1"> Category Name <span
                                    style="color: deeppink">*</span> </label>

                            <div class="col-xs-12 col-sm-8 @error('hall_category') has-error @enderror">
                                {{-- {!! Form::select('name', $room_category, $room->room_category, ['class' => 'form-control chosen-select', 'id' => 'category', 'placeholder' => 'Select Room Name', 'required']) !!} --}}
                                {!! Form::select('hall_category', $room_category, $room->hall_category, [
                                    'class' => 'form-control chosen-select',
                                    'id' => 'category',
                                    'placeholder' => 'Select Category',
                                    'onchange' => 'checkRoomNumberByCategory(this)',
                                    'required',
                                ]) !!}

                                @error('hall_category')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1"> Hall Number <span
                                    style="color: deeppink">*</span></label>

                            <div class="col-xs-12 col-sm-8 @error('room_number') has-error @enderror">
                                {{-- W4.2: kept raw. The checkRoomNumber() JS hook binds
                                     to onkeyup="checkRoomNumber(this)". --}}
                                <input type="text" class="form-control input-sm" name="hall_number"
                                    id="roomNumber" value="{{ $room->room_number }}"
                                    placeholder="Enter Room Number" required
                                    onkeyup="checkRoomNumber(this)">
                                <p id="duplicateRoomError" style="color: red; display: none;"></p>
                                @error('room_number')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <x-mm.field label="Size SQ" id="hall-sqft" name="hall_sqft" value="{{ old('hall_sqft', $room->hall_sqft) }}" placeholder="Enter Size SQ" />
                    </div>

                    {{-- Rent  --}}
                    <div class="col-sm-12">
                        <x-mm.field label="Rent" id="hall-price" name="price" value="{{ old('price', $room->price) }}" placeholder="Price" />
                    </div>

                    {{-- Max Guests  --}}
                    <div class="col-sm-12">
                        <x-mm.field label="Max Guests" id="hall-max-guests" name="max_guests" value="{{ old('max_guests', $room->max_guests) }}" placeholder="Enter Max Guests" />
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1"> Status</label>

                            <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                <select name="status" class="form-control chosen-select">
                                    <option></option>
                                    <option value="1" {{ $room->status == 1 ? 'selected' : '' }}>
                                        Ready
                                    </option>
                                    <option value="0" {{ $room->status == 0 ? 'selected' : '' }}>
                                        Dirty
                                    </option>
                                    <option value="2" {{ $room->status == 2 ? 'selected' : '' }}>
                                        Maintenance
                                    </option>
                                </select>
                                @error('status')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1">Smoking
                                Status</label>

                            <div class="col-xs-12 col-sm-8 @error('smoking_status') has-error @enderror">
                                <select name="smoking_status" class="form-control chosen-select">
                                    <option></option>
                                    <option value="No"
                                        {{ $room->smoking_status == 'No' ? 'selected' : '' }}>No</option>
                                    <option value="Yes"
                                        {{ $room->smoking_status == 'Yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('status')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-sm-12" style="display: none">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="form-field-1-1">Date :</label>
                            <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="fa fa-calendar"></span>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm date-picker"
                                            name="from_date"
                                            value="{{ request('from_date', date('Y-m-d')) }}"
                                            autocomplete="off">
                                        <span class="input-group-addon">From|To</span>
                                        <input type="text" class="form-control input-sm date-picker"
                                            name="to_date"
                                            value="{{ request('to_date', date('Y-m-d')) }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-actions center" style="text-align: right !important;">
                    <button type="submit" class="mm-button" id="submitRoomFormBtn"
                        onclick="submitRoomStoreForm(this)">
                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        Update
                    </button>
                </div>
            </form>
        </x-mm.panel>
    </x-mm.page>
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
