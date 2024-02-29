<?php

namespace Module\Hotel\Models;

use App\Models\User;

class RoomLog extends Model
{


    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function booked()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function received()
    {
        return $this->belongsTo(User::class, 'received_by');
    }



}
