@extends('layouts.master')

@section('title', 'Add Booking')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add New Hall Booking
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .table {
            margin-bottom: 0 !important;
        }

        body {
            counter-reset: section;
            /* Set a counter named 'section', and its initial value is 0. */
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }

        select:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        select:invalid[multiple] {
            margin-top: 15px !important;
        }
    </style>
    <style>
        .page-content {
            /* overflow-x: hidden; */
        }

        .ms-1 {
            margin-left: 5px !important;
        }

        .me-1 {
            margin-right: 5px !important;
        }

        .ms-2 {
            margin-left: 10px !important;
        }

        .me-2 {
            margin-right: 10px !important;
        }

        .ms-3 {
            margin-left: 15px !important;
        }

        .me-3 {
            margin-right: 15px !important;
        }
    </style>


    @include('hall_booking._css.css')
@endpush


@section('content')
    @php
        $date = date('Y-m-d');
        $date1 = str_replace('-', '/', $date);
        $tomorrow = date('Y-m-d', strtotime($date1 . '+1 days'));

    @endphp

    <div class="row">


        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('banquet.booking.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Booking List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" id="submitBookingUpdateForm" action="{{ route('banquet.booking.store') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" value="0" class="is-booking-edit" name="is_from_booking_edit">
                            <input type="hidden" value="1" name="is_from_booking">

                            @include('partials._alert_message')
                            @include('hall_booking._modal._guest-details-modal')


                            <!------------ INCLUDE GUEST INPUT FIELDS ------------>
                            <div class="row">
                                @include('hall_booking._inc._add-guest-input-info')
                            </div>


                            <!-- Room Information Table -->
                            <div class="row">
                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Booked Information</h3>

                                    {{-- Booking Table --}}
                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>
                                            <tr>
                                                {{-- <td width="25%">Room Category<span class="text-danger">*</span></td> --}}
                                                <td class="text-left">Room<span class="text-danger">*</span></td>
                                                <td class="text-center" style="width: 10%">Guest</td>
                                                <td class="text-left" width="15%">Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="text-center">Booked</td>
                                                <td class="text-right">Discount</td>
                                                {{-- <td class="text-center">Breakfast</td> --}}
                                                <td class="text-right" width="15%">T. Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="text-center" style="width: 5%">
                                                    <button type="button" class="btn btn-xs btn-success" id="addrow">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody class="item-details room-details-tbody">

                                        </tbody>
                                    </table>

                                    {{-- Restaurant Item Table --}}
                                    <table id="myTable" class="table table-bordered order-list mt-3">
                                        <thead>
                                            <tr>
                                                {{-- <td width="25%">Room Category<span class="text-danger">*</span></td> --}}
                                                <td class="text-left">Restaurant Item<span class="text-danger">*</span></td>
                                                <td class="text-center" width="10%">Qty</td>
                                                <td class="text-left" width="20%">Amount <span
                                                        class="currency-sign"></span></td>
                                                {{-- <td class="text-right">Infant</td>
                                                <td class="text-center">Booked</td> --}}
                                                <td class="text-right" width="20%">Discount</td>
                                                {{-- <td class="text-center">Breakfast</td> --}}
                                                <td class="text-right" width="20%">T. Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="text-center" style="width: 5%">
                                                    <button type="button" class="btn btn-xs btn-success" id="addItem">
                                                        <i class="fa fa-plus"></i>
                                                    </button>

                                                    {{-- <button type="button" class="btn btn-xs btn-warning" id="addItem">
                                                        <i class="fa fa-plus"></i>
                                                    </button> --}}
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody class="item-details item-details-tbody">

                                        </tbody>

                                        <!-- TFOOT INCLUDE -->
                                        {{-- @include('hall_booking._inc.create-edit-tfoot') --}}
                                    </table>
                                    {{-- Input Item Table --}}
                                    <table id="myTable" class="table table-bordered order-list mt-3">
                                        <thead>
                                            <tr>
                                                {{-- <td width="25%">Room Category<span class="text-danger">*</span></td> --}}
                                                <td class="text-left">Item<span class="text-danger">*</span></td>
                                                <td class="text-center" style="width: 10%">Qty</td>
                                                <td class="text-left" width="20%"">Amount <span
                                                        class="currency-sign"></span></td>
                                                {{-- <td class="text-right">Infant</td>
                                                <td class="text-center">Booked</td> --}}
                                                <td class="text-right" width="20%">Discount</td>
                                                {{-- <td class="text-center">Breakfast</td> --}}
                                                <td class="text-right" width="20%">T. Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="text-center" style="width: 5%">
                                                    <button type="button" class="btn btn-xs btn-success" id="addInformation">
                                                        <i class="fa fa-plus"></i>
                                                    </button>

                                                    {{-- <button type="button" class="btn btn-xs btn-warning" id="addItem">
                                                        <i class="fa fa-plus"></i>
                                                    </button> --}}
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody class="item-details info-details-tbody">

                                        </tbody>

                                        <!-- TFOOT INCLUDE -->
                                        @include('hall_booking._inc.create-edit-tfoot')

                                    </table>
                                </div>
                            </div>


                            @include('hall_booking/_modal/member-detail-modal')

                        </form>


                        <!-- ACTION/SUBMIT FORM -->
                        <div class="row my-2" style="margin-top: 5px; padding-right: 65px;">
                            <div class="btn-group btn-cro pull-right me-2">
                                <button type="button" name="type" value="reserve" onclick="submitBookingForm()"
                                    class="updateBookingBtn btn-outline-primary btn-sm next-step-btn no-border">
                                    <i class="fad fa-box-check"></i> Reserve
                                </button>
                                <button type="button" name="type" value="book" onclick="submitBookingForm()"
                                    class="updateBookingBtn btn-outline-info btn-sm next-step-btn no-border">
                                    <i class="fal fa-paper-plane"></i> Book Now
                                </button>
                                <button class="btn-sm btn-outline-danger" type="Reset">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </div>
    </div>
    @include('partials/modal/new_guest_modal')
    @include('partials/modal/edit_v1_guest_modal')

@endsection

@section('script')

    @include('hall_booking._script.script')


@endsection

{{-- <textarea name="check_in_note" id="check_in_note" cols="145" placeholder="Enter Your Info" rows="3"></textarea> --}}
