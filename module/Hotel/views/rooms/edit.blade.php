

@extends('layouts.master')

@section('title', 'Room Manage')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-room-form" title="Edit room" description="Configure room details, availability status and capacity.">
        <x-slot name="actions">
            <a href="{{ route('rooms.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Room inventory
            </a>
        </x-slot>
        <x-alert-message />
        <x-mm.panel class="render-class">
            @php
                setting('room_wise_pricing_booking');
            @endphp
<form class="form-horizontal" id="companyForm"
    action="{{ route('rooms.update', $room->id) }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" id="roomId" value="{{ $room->id }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="category"> Room Name <span
                        style="color: deeppink">*</span> </label>

                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                    {{-- {!! Form::select('name', $room_category, $room->room_category, ['class' => 'form-control chosen-select', 'id' => 'category', 'placeholder' => 'Select Room Name', 'required']) !!} --}}
                    {!! Form::select('name', $room_category, $room->room_category, [
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
                <label class="col-sm-3 control-label" for="roomNumber"> Room Number <span
                        style="color: deeppink">*</span></label>

                <div class="col-xs-12 col-sm-8 @error('room_number') has-error @enderror">
                    <input type="text" class="form-control input-sm" name="room_number"
                        id="roomNumber" value="{{ $room->room_number }}"
                        placeholder="Enter Room Number" required
                        onkeyup="checkRoomNumber(this)">
                    <p id="duplicateRoomError" role="status" aria-live="polite" style="color: red; display: none;"></p>
                    @error('room_number')
                        <span class="text-danger"> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="room-card"> F R ID Card
                </label>
                <div class="col-xs-12 col-sm-8 @error('f_r_id_card') has-error @enderror">
                    <input type="text" class="form-control input-sm" id="room-card" name="f_r_id_card"
                        value="{{ old('f_r_id_card', $room->f_r_id_card) }}"
                        placeholder="Enter F R ID Card">

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
                    <label class="col-sm-3 control-label" for="room-rent"> Rent/Night </label>
                    <div class="col-xs-12 col-sm-8 @error('rent') has-error @enderror">
                        <input type="text" class="form-control input-sm" id="room-rent" name="rent"
                            value="{{ old('rent', $room->rent) }}" placeholder="Rent/Night">

                        @error('rent')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Beds  --}}
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="room-beds"> Beds </label>
                    <div class="col-xs-12 col-sm-8 @error('beds') has-error @enderror">
                        <input type="text" class="form-control input-sm" id="room-beds" name="beds"
                            value="{{ old('beds', $room->beds) }}" placeholder="Enter Beds">

                        @error('beds')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Max Guests  --}}
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="room-max-guests"> Max Guests </label>
                    <div class="col-xs-12 col-sm-8 @error('max_guests') has-error @enderror">
                        <input type="text" class="form-control input-sm" id="room-max-guests" name="max_guests"
                            value="{{ old('max_guests', $room->max_guests) }}" placeholder="Enter Max Guests">

                        @error('max_guests')
                            <span class="text-danger"> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        @endif

        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="room-status"> Status</label>

                <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                    <select id="room-status" name="status" class="form-control chosen-select">
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

        <div class="col-sm-12">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="room-smoking">Smoking
                    Status</label>

                <div class="col-xs-12 col-sm-8 @error('smoking_status') has-error @enderror">
                    <select id="room-smoking" name="smoking_status" class="form-control chosen-select">
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
        </div>

        <div class="col-sm-12" style="display: none">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="room-from-date">Date :</label>
                <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                    <div class="input-group">
                        <div class="input-group-addon"><span class="fa fa-calendar"></span>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control input-sm date-picker"
                                id="room-from-date" name="from_date"
                                value="{{ request('from_date', date('Y-m-d')) }}"
                                autocomplete="off">
                            <span class="input-group-addon">From|To</span>
                            <input type="text" class="form-control input-sm date-picker"
                                aria-label="To date" id="room-to-date" name="to_date"
                                value="{{ request('to_date', date('Y-m-d')) }}"
                                autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="form-actions mm-room-form-actions">
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
