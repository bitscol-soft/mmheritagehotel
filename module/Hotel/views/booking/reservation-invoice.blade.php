@extends('layouts.master')
@section('title', 'Invoice')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    {{-- W3.1: round-6 invoice-doc styles. The previous inline <style>
         block (Calistoga font, col-print-N floats, .print-body border,
         etc.) has been removed; equivalent styles live in
         module/Hotel/views/booking/_css/invoice-sheet.blade.php. --}}

    @include('booking._css.invoice-sheet')
@stop

@section('content')

    <x-mm.styles />
    <x-mm.page class="mm-invoice-page" title="Reservation invoice" description="Review the reservation invoice and print it. Printing outputs the document only.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('booking.index') }}">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Booking List
            </a>
            {{-- W3.1: the print button is provided by the <x-mm.print-sheet>
                 footer below (the data-mm-print hook calls window.print()).
                 The old `printPage('print_body')` jQuery path used
                 `printThis` against the #print_body div; the new path
                 uses the browser's print pipeline, with the round-6
                 @media print rules in _css/invoice-sheet hiding the
                 surrounding admin chrome. --}}
        </x-slot>
        <x-mm.panel class="tw-p-4">
            @if (hasPermission('service.view', $slugs))
            <x-mm.print-sheet>
            <div class="invoice-doc">

            <!-- W3.1: round-6 inv-head. Brand on the left, doctitle on the
                 right. The blue bottom-border (border-bottom: 2px solid #2f63a8)
                 is provided by .invoice-doc .inv-head. -->
            <header class="inv-head">
                <div class="inv-brand">
                    <h3>{{ $company->name != null ? $company->name : '' }}</h3>
                    <p>{{ $company->head_office != null ? $company->head_office : '' }}</p>
                    <p>{{ $company->phone_number != null ? $company->phone_number : '' }}, {{ $company->email != null ? $company->email : '' }}</p>
                </div>
                <div class="inv-doctitle">
                    <div class="inv-kind">{{ $booking->status == 0 ? 'Reservation' : 'Booking' }} Confirmation</div>
                    <div class="inv-no">No. {{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}</div>
                    <div class="inv-printed">Printed: {{ date('F j, Y') }}</div>
                </div>
            </header>

            <!-- W3.1: round-6 inv-panels. Three panels with key/value
                 rows (date, company, address, contact, etc.) styled by
                 .inv-defrow. -->
            <section class="inv-panels">
                <div class="inv-panel">
                    <div class="inv-panel-title">Booking</div>
                    <div class="inv-defrow"><span>Date</span><span>{{ $booking->booking_date != null ? $booking->booking_date : 'N\A' }}</span></div>
                    @if (optional($booking->guestInfo)->company_id != null)
                        <div class="inv-defrow"><span>Company</span><span>{{ getCrmCompany(optional($booking->guestInfo)->company_id) }}</span></div>
                    @endif
                    <div class="inv-defrow"><span>Address</span><span>
                        @if (optional($booking->guestInfo)->company_id != null && getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                            {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}
                        @else
                            {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}
                        @endif
                    </span></div>
                </div>
                <div class="inv-panel">
                    <div class="inv-panel-title">Guest</div>
                    <div class="inv-defrow"><span>Confirmation No.</span><span>{{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}</span></div>
                    <div class="inv-defrow"><span>Cell No.</span><span>{{ optional($booking->guestInfo)->phone_no != null ? optional($booking->guestInfo)->phone_no : 'N\A' }}</span></div>
                    <div class="inv-defrow"><span>E-mail</span><span>{{ optional($booking->guestInfo)->email != null ? optional($booking->guestInfo)->email : 'N\A' }}</span></div>
                    <div class="inv-defrow"><span>NID/Passport No</span><span>{{ optional($booking->guestInfo)->nid_no != null ? optional($booking->guestInfo)->nid_no : 'N\A' }}</span></div>
                </div>
            </section>

            {{-- W3.1: keep the rest of the body content (greeting, guest/stay/room
                 details, notes, payment, signatures) below the panels. The PHP
                 expressions are byte-identical to the original; only the wrapping
                 class structure is new. --}}
            <div class="invoice-content-legacy">

                        <!-- LEFT SIDE -->
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Date</b></div>
                                </div>
                                <div class="col-print-9">
                                    <div class="col-text font-family">: {{ $booking->booking_date != null ? $booking->booking_date : 'N\A' }}</div>
                                </div>
                            </div>
                            @if (optional($booking->guestInfo)->company_id != null)
                                <div class="row">
                                    <div class="col-print-3">
                                        <div class="col-title font-family"><b>Company</b></div>
                                    </div>
                                    <div class="col-print-9">
                                        <div class="col-text font-family">: {{ getCrmCompany(optional($booking->guestInfo)->company_id) }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Address</b></div>
                                </div>
                                <div class="col-print-9">
                                    @if (optional($booking->guestInfo)->company_id != null && getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                                        <div class="col-text font-family">: {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}</div>
                                    @else
                                        <div class="col-text font-family">: {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-print-3">
                            <span style="color: white">.</span>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-print-5">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Confirmation No.</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Cell No.</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->phone_no != null ? optional($booking->guestInfo)->phone_no : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>E-mail</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family" style="word-break: break-all">: {{ optional($booking->guestInfo)->email != null ? optional($booking->guestInfo)->email : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>NID/Passport No</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->nid_no != null ? optional($booking->guestInfo)->nid_no : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- INVOICE CONTENT -->
            <div class="invoice-content">

                <b class="guest-name font-family">
                    Dear {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}
                </b>
                <div class="font-family">
                    Seasons best greetings from <b class="font-family">{{ $company->name }}</b>, We are pleased to confirm the following reservation as per your request.
                </div>

                <!-- GUEST INFO -->
                <div class="guest-info">
                    <div class="row">
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-4">
                                    <div class="col-title font-family"><b>Guest Name</b></div>
                                </div>
                                <div class="col-print-8">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Father's Name</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->father_name != null ? optional($booking->guestInfo)->father_name : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-4">
                                    <div class="col-title font-family"><b>Age</b></div>
                                </div>
                                <div class="col-print-8">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->age != null ? optional($booking->guestInfo)->age : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-4">
                                    <div class="col-title font-family"><b>Eg.Contact</b></div>
                                </div>
                                <div class="col-print-8">
                                    <div class="col-text font-family">: {{ $booking->emergency_cont_name != null ? $booking->emergency_cont_name : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Eg.Contact</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->emergency_cont_phone != null ? $booking->emergency_cont_phone : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-4">
                                    <div class="col-title font-family"><b>Profession</b></div>
                                </div>
                                <div class="col-print-8">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->profession != null ? optional($booking->guestInfo)->profession : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-print-4"></div> --}}
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-6">
                                    <div class="col-title font-family"><b>Expected Arrival Date</b></div>
                                </div>
                                <div class="col-print-6">
                                    <div class="col-text font-family">: {{ $booking->check_in_date != null ? $booking->check_in_date : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-6">
                                    <div class="col-title font-family"><b>Expected Departure Date</b></div>
                                </div>
                                <div class="col-print-6">
                                    <div class="col-text font-family">: {{ $booking->check_out_date != null ? $booking->check_out_date : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-print-1" style="color: white">.</div> --}}
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-3" style="padding-left: 25px">
                                    <div class="col-title font-family"><b>Pickup</b></div>
                                </div>
                                <div class="col-print-9">
                                    <div class="col-text font-family">: {{ $booking->pickup != null ? $booking->pickup : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-3" style="padding-left: 25px">
                                    <div class="col-title font-family"><b>Drop</b></div>
                                </div>
                                <div class="col-print-9">
                                    <div class="col-text font-family">: {{ $booking->drop != null ? $booking->drop : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Flight No./Time</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->pickup_flight != null ? $booking->pickup_flight : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Flight No./Time</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->drop_flight != null ? $booking->drop_flight : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROOM INFO -->
                <div class="guest-room-info">
                    <div class="row">
                        <div class="col-print-8">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Room Type</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">:
                                        @foreach ($booking->bookingDetails->unique('category_id') as $item)
                                            {{ optional($item->roomCategory)->name }}
                                            @if(!$loop->last),@endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>PAX</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->booking_pax ?? 'N\A' }} </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-print-8">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Adult PAX</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->adult_pax ?? 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Child PAX</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->child_pax ?? 'N\A' }}</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row" @if ($booking->status == 0) style="display: none" @endif>
                        <div class="col-print-8">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Room Numbers</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">:
                                        @foreach ($booking->bookingDetails as $item)
                                            {{ optional($item->roomNumber)->room_number }}
                                            @if(!$loop->last),@endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Total Night</b></div>
                                </div>
                                <div class="col-print-7" style="padding-left: 0px !important;">
                                    <div class="col-text font-family">:
                                        {{ optional($booking->bookingDetails[0])->night_count }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-print-8">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Mode of Payment</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->payment_way != null ? $booking->payment_way : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Smoking Status</b></div>
                                </div>
                                <div class="col-print-7" style="padding-left: 0px !important;">
                                    <div class="col-text font-family">: {{ optional($item->roomNumber)->smoking_status != null ? optional($item->roomNumber)->smoking_status : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-print-8">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Reference By</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->reference != null ? $booking->reference : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            @if ($booking->purpose_id != null)
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Booking Purpose</b></div>
                                </div>
                                <div class="col-print-7" style="padding-left: 0px !important;">
                                    <div class="col-text font-family">:
                                        {{ optional($booking->booking_purpose)->name }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                    </div>

                    <div class="row">
                        {{-- <div class="col-print-8">
                            <div class="row" style="margin-top: 0px;">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Remarks</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->check_in_note != null ? $booking->check_in_note : 'N\A' }}</div>
                                </div>
                            </div>
                        </div> --}}
                        @if ($booking->booking_platform != null)
                        <div class="col-print-8">
                            <div class="row" style="margin-top: 0px;">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Platform</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->booking_platform != null ? $booking->booking_platform->name : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-print-8">
                            <div class="row" style="margin-top: 0px;">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Check In</b></div>
                                </div>
                                <div class="col-print-8" style="padding-left: 6px !important;">
                                    <div class="col-text font-family">: {{ $booking->check_in_time ? date("F j, Y, g:i a", strtotime($booking->check_in_time)) : '' }} </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Check Out</b></div>
                                </div>
                                <div class="col-print-7" style="padding-left: 0px !important;">
                                    <div class="col-text font-family">:
                                        {{ $booking->check_out_time ? date("F j, Y, g:i a", strtotime($booking->check_out_time)) : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOOKING NOTE -->
                <div class="note">

                    <p class="note-title font-family">Please Note:</p>
                    {{-- {!! $bookingNotes->title !!} --}}
                    @foreach ($bookingNotes as $bookingNote)
                        <div class="item">
                            <div class="d-flex">
                                <b class="item-icon">
                                    #
                                </b>
                                <div class="item-text">
                                    {{ $bookingNote->title }}
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- @php
                        $checkInOut = setting('date_start_end') != null ? setting('date_start_end') : '';
                        $checkIn    = '';
                        $checkOut   = '';

                        if ($checkInOut != null) {
                            $explode    = explode("-", $checkInOut);
                            $checkIn    = $explode[0];
                            $checkOut   = $explode[1];
                        }
                    @endphp
                    <div class="item">
                        <div class="d-flex">
                            <b class="item-icon">
                                #
                            </b>
                            <div class="item-text">
                                Our standard check in time is {{ $checkIn }} & check out time is {{ $checkOut }}.
                            </div>
                        </div>
                    </div> --}}

                </div>

            </div>

            <!-- PRINT FOOTER -->
            <div class="print-footer">
                <b class="footer-note font-family"><span class="footer-hash font-family">#</span>please provide us your estimated time of arrival in order to get your room ready upon arrival</b>
                <div class="ending-message font-family">Thank you again for showing interest in <b class="font-family">{{ $company->name }}</b>. Is there anything we can do to make your stay more rewarding, please as</div>
                <div class="with-regards font-family">With Best Regards</div>
                <div class="footer-name font-family">{{ $company->name }}</div>
                @if ($company->head_office != null) <div class="font-family footer-company-address">{{ $company->head_office }}</div> @endif
            </div>
            <div class="inv-sign signature-sectuion" style="display: flex; justify-content: space-evenly;    margin: 29px 0 0 0;">
                <div class="sig text-center" style="margin-right: 10px;">
                    <span class="text-center" style="border-top: 1px solid #c3c3c3; padding: 5px 82px;">Guest</span>
                </div>
                <div class="sig text-center" style="margin-left: 10px;">
                    <span class="text-center" style="border-top: 1px solid #c3c3c3; padding: 5px 82px;">{{ $company->name }}</span>
                </div>
            </div>
            </div>{{-- /.invoice-content-legacy --}}

            </div>{{-- /.invoice-doc --}}
            </x-mm.print-sheet>
            @endif
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    {{-- W3.1: the printThis jQuery plugin and the printPage() function
         are no longer used. The <x-mm.print-sheet> footer provides
         a print button that calls window.print(), and the round-6
         @media print rules in _css/invoice-sheet hide the surrounding
         admin chrome. --}}
@stop
