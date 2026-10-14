@extends('layouts.master')

@section('title', 'Add Booking')

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

    @include('booking._css.css')
@endpush

@section('content')
    @php
        $date = today_from_system();
        $date1 = str_replace('-', '/', $date);
        $tomorrow = date('Y-m-d', strtotime($date1 . '+1 days'));

    @endphp

    <x-mm.styles />
    <x-mm.page class="mm-booking-next" title="New booking" description="Choose the guest and stay dates, then add rooms and payment details.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('booking.index') }}">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Booking List
            </a>
        </x-slot>
        <x-mm.panel>
                    <div>

                        <form class="form-horizontal" id="submitBookingUpdateForm" action="{{ route('booking.store') }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" value="0" class="is-booking-edit" name="is_from_booking_edit">
                            <input type="hidden" value="1" name="is_from_booking">

                            @include('partials._alert_message')
                            @include('booking._modal._guest-details-modal')

                            <!------------ INCLUDE GUEST INPUT FIELDS ------------>
                            <div class="row">
                                @include('booking._inc._add-guest-input-info')
                            </div>

                            <!-- Room Information Table -->
                            <div class="row">
                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Room Information</h3>
                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>
                                            <tr>
                                                <td width="25%">Room Category<span class="text-danger">*</span></td>
                                                <td class="text-left">Room<span class="text-danger">*</span></td>
                                                <td class="text-center" style="width: 10%">Guest</td>
                                                <td class="text-left" width="8%">Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="text-right">Infant</td>
                                                <td class="text-right">Child</td>
                                                <td class="text-right">Night</td>
                                                <td class="text-right">Discount</td>
                                                <td class="text-right">Discount Type</td>
                                                <td class="text-center breakfast_qty">BF Qty</td>
                                                <td class="text-center">Breakfast</td>
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

                                        <!-- TFOOT INCLUDE -->
                                        @include('booking._inc.create-edit-tfoot')
                                    </table>
                                </div>
                            </div>

                            @include('booking/_modal/member-detail-modal')

                        </form>

                        <!-- ACTION/SUBMIT FORM -->
                        <div class="mm-form-actions">
                            <button type="button" name="type" value="reserve" onclick="submitBookingForm()"
                                class="updateBookingBtn next-step-btn mm-button mm-button-secondary">
                                <i class="fa fa-bookmark" aria-hidden="true"></i> Reserve
                            </button>
                            <button type="button" name="type" value="book" onclick="submitBookingForm()"
                                class="updateBookingBtn next-step-btn mm-button">
                                <i class="fa fa-paper-plane" aria-hidden="true"></i> Book Now
                            </button>
                            <button class="mm-button mm-button-secondary" type="Reset">
                                <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                            </button>
                        </div>
                    </div>
        </x-mm.panel>
    </x-mm.page>
    @include('partials/modal/new_guest_modal')
    @include('partials/modal/edit_v1_guest_modal')

@endsection

@section('script')

    @include('booking._script.script')
    <script src="{{ asset('assets/custom_js/stay-dates.js') }}"></script>

@endsection
