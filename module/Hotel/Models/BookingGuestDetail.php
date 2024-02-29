<?php

namespace Module\Hotel\Models;

use App\Model;

class BookingGuestDetail extends Model
{


    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
