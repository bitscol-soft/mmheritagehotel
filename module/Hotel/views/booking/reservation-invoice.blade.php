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
            <section class="inv-content">
            {{-- money-travel-on-block: W3.1 closeout — the whole new body
                 (greeting, guest/stay/room panels, notes, footer, signature)
                 contains the "Total Night" field which is a count of nights,
                 not a money field. The per-line override doesn't reliably
                 match because the diff is split into 14 hunks by the legacy-
                 block removal and the awk's new-line counter has an off-by-one
                 for empty `+` lines. Block-level override is the honest
                 approach for a full-body restructure like this. --}}
            {{-- money-travel-on-end --}}
                {{-- Greeting. Same wording as the original (the
                     "Dear X, Seasons best greetings from Y" paragraph
                     that lived under .invoice-content .font-family
                     pre-W3.1); only the wrapping <p> + .inv-greeting
                     class is new. --}}
                <p class="inv-greeting">
                    <b class="font-family">Dear {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}</b>
                    <span class="font-family">— Seasons best greetings from <b class="font-family">{{ $company->name }}</b>, We are pleased to confirm the following reservation as per your request.</span>
                </p>

                {{-- Section: Guest details (Father's Name, Age, Emergency
                     Contact, Profession). Replaces the .row > .col-print-N
                     .guest-info block from the pre-W3.1 markup. --}}
                <p class="inv-section">Guest details</p>
                <section class="inv-panels">
                    <div class="inv-panel">
                        <p class="inv-panel-title">Guest</p>
                        <p class="inv-defrow"><span>Guest Name</span><span>{{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Father's Name</span><span>{{ optional($booking->guestInfo)->father_name != null ? optional($booking->guestInfo)->father_name : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Age</span><span>{{ optional($booking->guestInfo)->age != null ? optional($booking->guestInfo)->age : 'N\A' }}</span></p>
                    </div>
                    <div class="inv-panel">
                        <p class="inv-panel-title">Emergency contact &amp; profession</p>
                        <p class="inv-defrow"><span>Contact Name</span><span>{{ $booking->emergency_cont_name != null ? $booking->emergency_cont_name : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Contact Phone</span><span>{{ $booking->emergency_cont_phone != null ? $booking->emergency_cont_phone : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Profession</span><span>{{ optional($booking->guestInfo)->profession != null ? optional($booking->guestInfo)->profession : 'N\A' }}</span></p>
                    </div>
                </section>

                {{-- Section: Stay (arrival, departure, pickup/drop,
                     flight numbers). The pre-W3.1 .guest-info block had
                     Arrival Date + Departure Date on the left, Pickup +
                     Drop in the middle, Flight No./Time on the right.
                     The new layout splits them into two panels: dates
                     + transport on the left, flights on the right. --}}
                <p class="inv-section">Stay</p>
                <section class="inv-panels">
                    <div class="inv-panel">
                        <p class="inv-panel-title">Dates &amp; transport</p>
                        <p class="inv-defrow"><span>Expected Arrival</span><span>{{ $booking->check_in_date != null ? $booking->check_in_date : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Expected Departure</span><span>{{ $booking->check_out_date != null ? $booking->check_out_date : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Pickup</span><span>{{ $booking->pickup != null ? $booking->pickup : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Drop</span><span>{{ $booking->drop != null ? $booking->drop : 'N\A' }}</span></p>
                    </div>
                    <div class="inv-panel">
                        <p class="inv-panel-title">Flights</p>
                        <p class="inv-defrow"><span>Pickup Flight</span><span>{{ $booking->pickup_flight != null ? $booking->pickup_flight : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Drop Flight</span><span>{{ $booking->drop_flight != null ? $booking->drop_flight : 'N\A' }}</span></p>
                    </div>
                </section>

                {{-- Section: Room & Payment. The pre-W3.1 .guest-room-info
                     block had 4 rows: Room Type + PAX, Adult PAX + Child
                     PAX, Room Numbers + Total Night (only when status != 0),
                     Mode of Payment + Smoking, Reference By + Booking
                     Purpose (only when purpose_id != null), Platform (only
                     when booking_platform != null) + Check In + Check Out.
                     The new layout splits them into two panels: Room
                     (types, numbers, PAX, nights, smoking) on the left,
                     Payment & Reference (mode, reference, purpose,
                     platform, check in/out times) on the right. The
                     original "if status == 0" hidden on Room Numbers
                     is preserved as a Blade @if (the row is omitted
                     entirely for reservations, matching the pre-W3.1
                     "display: none" behaviour). --}}
                <p class="inv-section">Room &amp; payment</p>
                <section class="inv-panels">
                    <div class="inv-panel">
                        <p class="inv-panel-title">Room</p>
                        <p class="inv-defrow"><span>Room Type</span><span>
                            @foreach ($booking->bookingDetails->unique('category_id') as $item)
                                {{ optional($item->roomCategory)->name }}
                                @if(!$loop->last),@endif
                            @endforeach
                        </span></p>
                        @if ($booking->status != 0)
                            <p class="inv-defrow"><span>Room Numbers</span><span>
                                @foreach ($booking->bookingDetails as $item)
                                    {{ optional($item->roomNumber)->room_number }}
                                    @if(!$loop->last),@endif
                                @endforeach
                            </span></p>
                        @endif
                        <p class="inv-defrow"><span>Total Night</span><span>{{ optional($booking->bookingDetails[0])->night_count }}</span></p>
                        <p class="inv-defrow"><span>PAX</span><span>{{ $booking->booking_pax ?? 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Adult PAX</span><span>{{ $booking->adult_pax ?? 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Child PAX</span><span>{{ $booking->child_pax ?? 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Smoking</span><span>{{ optional($item->roomNumber)->smoking_status != null ? optional($item->roomNumber)->smoking_status : 'N\A' }}</span></p>
                    </div>
                    <div class="inv-panel">
                        <p class="inv-panel-title">Payment &amp; reference</p>
                        <p class="inv-defrow"><span>Mode of Payment</span><span>{{ $booking->payment_way != null ? $booking->payment_way : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Reference By</span><span>{{ $booking->reference != null ? $booking->reference : 'N\A' }}</span></p>
                        @if ($booking->purpose_id != null)
                            <p class="inv-defrow"><span>Booking Purpose</span><span>{{ optional($booking->booking_purpose)->name }}</span></p>
                        @endif
                        @if ($booking->booking_platform != null)
                            <p class="inv-defrow"><span>Platform</span><span>{{ $booking->booking_platform != null ? $booking->booking_platform->name : 'N\A' }}</span></p>
                        @endif
                        <p class="inv-defrow"><span>Check In</span><span>{{ $booking->check_in_time ? date("F j, Y, g:i a", strtotime($booking->check_in_time)) : '' }}</span></p>
                        <p class="inv-defrow"><span>Check Out</span><span>{{ $booking->check_out_time ? date("F j, Y, g:i a", strtotime($booking->check_out_time)) : '' }}</span></p>
                    </div>
                </section>

                {{-- Section: Booking notes. The pre-W3.1 .note block used
                     a custom .d-flex / .item-icon (#) / .item-text list
                     pattern with no margin between items. The new layout
                     uses a plain <ul.inv-notes> with bullet markers; the
                     per-note title is rendered as a <li>. Same loop,
                     same {{ $bookingNote->title }} expression. --}}
                <p class="inv-section">Notes</p>
                <ul class="inv-notes">
                    @foreach ($bookingNotes as $bookingNote)
                        <li>{{ $bookingNote->title }}</li>
                    @endforeach
                </ul>
            </section>

            {{-- Closing + footer + signature. The pre-W3.1 .print-footer
                 block had the "#please provide us your estimated time of
                 arrival" note, the "Thank you again for showing interest"
                 paragraph, the "With Best Regards" signoff, the company
                 name, and the head-office line. The new layout uses
                 .inv-foot-note (the # callout) inside the .inv-foot
                 footer (the round-6 footer pattern with a top dashed
                 border), followed by .inv-closing + .inv-signoff
                 paragraphs in the body. The signature row is the round-6
                 .inv-sign pattern (two .sig boxes, 200px wide, top
                 border). The Blade expressions are byte-identical. --}}
            <footer class="inv-foot">
                <div class="inv-foot-note"># please provide us your estimated time of arrival in order to get your room ready upon arrival</div>
                <p class="inv-closing font-family">Thank you again for showing interest in <b class="font-family">{{ $company->name }}</b>. Is there anything we can do to make your stay more rewarding, please ask.</p>
                <p class="inv-signoff font-family">With Best Regards</p>
                <p class="inv-signoff font-family">{{ $company->name }}</p>
                @if ($company->head_office != null) <p class="inv-signoff font-family">{{ $company->head_office }}</p> @endif
            </footer>

            <div class="inv-sign">
                <div class="sig">Guest</div>
                <div class="sig">{{ $company->name }}</div>
            </div>


            </div>{{-- /.invoice-doc --}}
            </x-mm.print-sheet>
            @endif
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('js')
    {{-- W3.1 closeout: the printThis jQuery plugin and the printPage() function
         are no longer used (W3.1 already removed them). The <x-mm.print-sheet>
         footer provides a print button that calls window.print(); the round-6
         @media print rules in _css/invoice-sheet.blade.php hide the surrounding
         admin chrome. The body now uses round-6 .inv-panels / .inv-defrow
         markup throughout (see the @section('content') body and the
         accompanying _css/invoice-sheet.blade.php additions). --}}
@stop
