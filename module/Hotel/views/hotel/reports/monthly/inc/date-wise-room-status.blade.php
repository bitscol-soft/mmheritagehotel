@if (optional($booking->guestInfo))
    <span class="popover-success pointer" data-rel="popover" data-placement="top" data-trigger="hover" style="color: transparent"
        data-original-title="<i class='fa fa-info-circle green'></i> Guest Information"
        data-content="<p class='tool-pen'>Name: {{ optional($booking->guestInfo)->name }}.</p> <p class='tool-pen'> Phone No : {{ optional($booking->guestInfo)->phone_no }}</p>
                <p class='tool-pen'> Passport : {{ optional($booking->guestInfo)->nid_no }}</p>
                {!! $booking->check_in_time ? "<p class='tool-pen'>Check In: $booking->check_in_time </p>" : '' !!}
                {!! $booking->check_out_time ? "<p class='tool-pen'>Check Out: $booking->check_out_time </p>" : '' !!}
                {!! $booking->check_in_note ? "<p class='tool-pen'>Note: $booking->check_in_note </p>" : '' !!}">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </span>
@endif
