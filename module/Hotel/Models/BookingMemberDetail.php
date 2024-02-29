<?php

namespace Module\Hotel\Models;


class BookingMemberDetail extends Model
{
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
