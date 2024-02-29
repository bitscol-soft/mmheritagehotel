<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class BookingPurpose extends Model
{

    protected $table = 'hotel_booking_purpose';

    protected $guarded = [];

    use AutoCreatedUpdated;


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


}
