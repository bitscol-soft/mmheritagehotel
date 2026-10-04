@extends('layouts.master')
@section('title', 'Invoice')

@section('css')
    {{-- W3.1-twin closeout: round-6 invoice-doc look. The pre-W3.1
         inline <style> block (Calistoga font, col-print-N floats,
         .print-body border, .company-info, .invoice-title,
         .customer-info, .note, .print-footer, .signature-sectuion,
         .reservation-row, .guest-info, .guest-room-info, .d-flex,
         .note-title, .item-icon, .footer-note, .footer-hash,
         .ending-message, .with-regards, .footer-name,
         .footer-company-address, etc.) has been removed; equivalent
         styles live in hall_booking/_css/invoice-sheet.blade.php
         (module-local mirror of module/Hotel/views/booking/_css/
         invoice-sheet.blade.php so the on-screen + print look is
         consistent across Hotel + BanquetHall reservation
         confirmations). --}}
    @include('hall_booking._css.invoice-sheet')
@stop


@section('content')

    {{-- W4.1 wrapped the body in <x-mm.page> + <x-mm.print-sheet> and
         added a cross-module @include('booking._css.invoice-sheet')
         so the round-6 .inv-head / .inv-panels / .inv-defrow CSS
         was loaded (defensively — the file still used the pre-W3.1
         .row / .col-print-N markup at the time).
         W3.1-twin closeout (this commit) replaces the pre-W3.1
         .row / .col-print-N / .print-body / .company-info / .invoice-
         title / .customer-info / .guest-info / .guest-room-info /
         .note / .print-footer / .signature-sectuion markup with the
         round-6 .invoice-doc → .inv-head + .inv-panels + .inv-content
         + .inv-foot + .inv-sign markup that checkout_invoice.blade.php
         (W3.2) and module/Hotel/views/booking/reservation-invoice.blade
         .php (W3.1 + W3.1 closeout 4a4c2726) already use. The
         cross-module @include is now a module-local
         @include('hall_booking._css.invoice-sheet') so each module
         owns its print styling. The print button is provided by
         <x-mm.print-sheet>'s footer (data-mm-print hook calls
         window.print()). The old jQuery `printPage('print_body')`
         path that used printThis against #print_body is gone. --}}
    <x-mm.styles />
    <x-mm.page class="mm-invoice-page mm-hall-invoice" title="Hall reservation invoice" description="Review the hall reservation invoice and print it. Printing outputs the document only.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('hall-booking.index') }}">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Hall booking list
            </a>
        </x-slot>
        <x-mm.panel class="tw-p-4">
            <x-mm.print-sheet>
            <div class="invoice-doc">

            {{-- Round-6 header. Brand on the left (company name + head
                 office + phone/email), doctitle on the right
                 (Reservation/Booking Confirmation + No. + Printed:
                 date). The blue bottom-border is provided by
                 .invoice-doc .inv-head. --}}
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

            {{-- Round-6 panels. The Booking panel shows Date + Company
                 (only when company_id is set) + Address. The Guest panel
                 shows Confirmation No. + Cell No. + E-mail + NID/Passport
                 No. Both panels use the same key/value row pattern as
                 checkout_invoice.blade.php. --}}
            <section class="inv-panels">
                <div class="inv-panel">
                    <p class="inv-panel-title">Booking</p>
                    <p class="inv-defrow"><span>Date</span><span>{{ $booking->booking_date != null ? $booking->booking_date : 'N\A' }}</span></p>
                    @if (optional($booking->guestInfo)->company_id != null)
                        <p class="inv-defrow"><span>Company</span><span>{{ getCrmCompany(optional($booking->guestInfo)->company_id) }}</span></p>
                    @endif
                    <p class="inv-defrow"><span>Address</span><span>
                        @if (optional($booking->guestInfo)->company_id != null && getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                            {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}
                        @else
                            {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}
                        @endif
                    </span></p>
                </div>
                <div class="inv-panel">
                    <p class="inv-panel-title">Guest</p>
                    <p class="inv-defrow"><span>Confirmation No.</span><span>{{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}</span></p>
                    <p class="inv-defrow"><span>Cell No.</span><span>{{ optional($booking->guestInfo)->phone_no != null ? optional($booking->guestInfo)->phone_no : 'N\A' }}</span></p>
                    <p class="inv-defrow"><span>E-mail</span><span>{{ optional($booking->guestInfo)->email != null ? optional($booking->guestInfo)->email : 'N\A' }}</span></p>
                    <p class="inv-defrow"><span>NID/Passport No</span><span>{{ optional($booking->guestInfo)->nid_no != null ? optional($booking->guestInfo)->nid_no : 'N\A' }}</span></p>
                </div>
            </section>

            {{-- money-travel-on-block: W3.1-twin closeout — the whole
                 new body is a round-6 restructure. The only
                 "Total"-sounding line is "Booked Time" (a time
                 field, not a money field). Block-level override is
                 the honest answer for a full-body restructure that
                 splits into 10+ diff hunks. --}}
            {{-- money-travel-on-end --}}
            <section class="inv-content">

                {{-- Greeting. Same wording as the pre-W3.1 markup
                     (the "Dear X, Seasons best greetings from Y"
                     paragraph that lived under .invoice-content
                     .font-family); only the wrapping <p> +
                     .inv-greeting class is new. --}}
                <p class="inv-greeting">
                    <b class="font-family">Dear {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}</b>
                    <span class="font-family">— Seasons best greetings from <b class="font-family">{{ $company->name }}</b>, We are pleased to confirm the following reservation as per your request.</span>
                </p>

                {{-- Section: Guest details (Father's Name, Age,
                     Emergency Contact, Profession). Replaces the
                     .row > .col-print-N .guest-info block from the
                     pre-W3.1 markup. --}}
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

                {{-- Section: Hall & payment. The pre-W3.1
                     .guest-room-info block had 4 rows: Room Type
                     (hardcoded "BanquetHall") on the left, Hall
                     Room Numbers + Booked Time (only when status
                     != 0) on the right, Mode of Payment on the
                     left, Reference By + Booking Purpose (only
                     when purpose_id != null) on the right,
                     Remarks + Check In on the left (no Check
                     Out for single-day banquets). The new layout
                     splits them into two panels: Hall (type,
                     numbers, time) on the left, Payment &
                     Reference (mode, reference, purpose, remarks,
                     check in) on the right. The original "if
                     status == 0" hidden on Hall Room Numbers is
                     preserved as a Blade @if. Banquet halls have
                     no Pickup/Drop/Flights/PAX/Adult PAX/Child
                     PAX/Smoking fields (those are hotel-only). --}}
                <p class="inv-section">Hall &amp; payment</p>
                <section class="inv-panels">
                    <div class="inv-panel">
                        <p class="inv-panel-title">Hall</p>
                        <p class="inv-defrow"><span>Room Type</span><span>BanquetHall</span></p>
                        @if ($booking->status != 0)
                            <p class="inv-defrow"><span>Hall Room Numbers</span><span>
                                @foreach ($booking->bookingDetails as $item)
                                    {{ optional($item->hall)->room_number }}
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </span></p>
                        @endif
                        <p class="inv-defrow"><span>Booked Time</span><span>{{ $booking->booked_time }}</span></p>
                    </div>
                    <div class="inv-panel">
                        <p class="inv-panel-title">Payment &amp; reference</p>
                        <p class="inv-defrow"><span>Mode of Payment</span><span>{{ $booking->payment_way != null ? $booking->payment_way : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Reference By</span><span>{{ $booking->reference != null ? $booking->reference : 'N\A' }}</span></p>
                        @if ($booking->purpose_id != null)
                            <p class="inv-defrow"><span>Booking Purpose</span><span>{{ optional($booking->booking_purpose)->name }}</span></p>
                        @endif
                        <p class="inv-defrow"><span>Remarks</span><span>{{ $booking->check_in_note != null ? $booking->check_in_note : 'N\A' }}</span></p>
                        <p class="inv-defrow"><span>Check In</span><span>{{ $booking->check_in_date ? date('F j, Y, g:i a', strtotime($booking->check_in_date)) : '' }}</span></p>
                    </div>
                </section>

                {{-- Section: Booking notes. The pre-W3.1 .note
                     block used a custom .d-flex / .item-icon (#) /
                     .item-text list pattern with no margin
                     between items. The new layout uses a plain
                     <ul.inv-notes> with bullet markers; the
                     per-note title is rendered as a <li>. Same
                     loop, same {{ $bookingNote->title }}
                     expression. --}}
                <p class="inv-section">Notes</p>
                <ul class="inv-notes">
                    @foreach ($bookingNotes as $bookingNote)
                        <li>{{ $bookingNote->title }}</li>
                    @endforeach
                </ul>
            </section>

            {{-- Closing + footer + signature. The pre-W3.1
                 .print-footer block had the
                 "#please provide us your estimated time of
                 arrival" note, the "Thank you again for showing
                 interest" paragraph, the "With Best Regards"
                 signoff, the company name, and the head-office
                 line. The new layout uses .inv-foot-note (the #
                 callout) inside the .inv-foot footer (the
                 round-6 footer pattern with a top dashed
                 border), followed by .inv-closing + .inv-signoff
                 paragraphs in the body. The signature row is
                 the round-6 .inv-sign pattern (two .sig boxes,
                 200px wide, top border). The Blade expressions
                 are byte-identical. --}}
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
            </x-mm.panel>
        </x-mm.page>
@endsection

@section('js')
    {{-- W4.1 removed the printThis.js script and the printPage()
         function. The print button is now provided by
         <x-mm.print-sheet>'s footer (data-mm-print hook calls
         window.print()). W3.1-twin closeout (this commit) doesn't
         add any new JS — the round-6 invoice-doc is pure CSS + the
         same @section('js') footprint. --}}
@stop
