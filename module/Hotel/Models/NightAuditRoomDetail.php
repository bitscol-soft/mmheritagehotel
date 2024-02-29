<?php

namespace Module\Hotel\Models;


class NightAuditRoomDetail extends Model
{
    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }
}
