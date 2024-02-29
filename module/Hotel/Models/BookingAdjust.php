<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class BookingAdjust extends Model
{

    use AutoCreatedUpdated;

    public function transactions()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }

    public function transaction()
    {
        return $this->morphOne(HotelTransection::class, 'source');
    }


}
