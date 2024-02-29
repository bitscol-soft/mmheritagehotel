@extends('layouts.pdf-master')
@section('title', 'Booking List')


@section('heading')

    <div class="head-main" style="width:100%; display: flex; flex-direction: row;">

        @if (file_exists('uploads/company/' . $company->logo))
            <div class="left" style="float: left; margin-left: -600px;">
                <img src="{{ 'uploads/company/' . $company->logo }}" alt="Company Logo" width="80" height="80">
            </div>

            <div class="right" style="margin-top: -80px; margin-left: 0%">
                <div style="margin: 0 auto; text-align: center;">
                    <h4 class="company-name font-family" style="text-transform:uppercase; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->name != null ? $company->name : '' }}</h4>
                    <p class="font-family" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->head_office != null ? $company->head_office : '' }}</p>
                    <p class="font-family" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->phone_number != null ? $company->phone_number : '' }}, {{ $company->email != null ? $company->email : '' }}</p>
                </div>
            </div>
        @else
            <div class="right" style="margin-left: 0%">
                <div style="margin: 0 auto; text-align: center;">
                    <h4 class="company-name font-family" style="text-transform:uppercase; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->name != null ? $company->name : '' }}</h4>
                    <p class="font-family" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->head_office != null ? $company->head_office : '' }}</p>
                    <p class="font-family" style="font-family: Helvetica Neue, Helvetica, Arial, sans-serif; line-height: 3px;">{{ $company->phone_number != null ? $company->phone_number : '' }}, {{ $company->email != null ? $company->email : '' }}</p>
                </div>
            </div>
        @endif
    </div>

    <h2 style="line-height: 3px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
        Booking List
    </h2>
    <h4 style="line-height: 3px; font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
        Date - {{ date('Y-m-d') }}
    </h4>
@endsection



@section('table')
    @include('booking.export.excel')
    <div class="text-right" style="margin-top: 3px">
        Printed at {{ now() }}
    </div>
@endsection
