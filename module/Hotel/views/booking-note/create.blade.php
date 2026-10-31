
@extends('layouts.master')
@section('title','Add Booking Note')
@section('content')
<x-mm.styles />
<x-mm.page title="Add booking note" description="Create a new booking note title." class="mm-booking-setup mm-booking-notes">
<x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('booking-note.index') }}">Back to notes</a></x-slot>
@include('partials._alert_message')
<x-mm.panel class="tw-p-5">
<form class="form-horizontal" id="companyForm" action="{{ route('booking-note.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">

                                    <hr>
                                    <div class="form-group">
                                        <label for="booking-note-title" class="col-sm-3 control-label">Title<sup class="text-danger">*</sup></label>

                                        <div class="col-xs-12 col-sm-8">
                                            <textarea name="title" id="booking-note-title" class="form-control" cols="30" rows="10" required>{{ old('title') }}</textarea>
@error('title')<p role="alert">{{ $message }}</p>@enderror
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class=" btn-sm btn-outline-success">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>

                                <a href="{{ route('booking-note.index') }}" class="btn-sm btn btn-default">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
                            </div>
                        </form>
</x-mm.panel>
</x-mm.page>
@endsection
