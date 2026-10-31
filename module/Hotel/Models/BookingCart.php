<?php

namespace Module\Hotel\Models;

use App\Model;

class BookingCart extends Model
{
    protected $table = 'booking_carts';

    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }

    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category_id');
    }
}
