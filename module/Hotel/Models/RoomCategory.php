<?php

namespace Module\Hotel\Models;

use App\Traits\AutoCreatedUpdated;

class RoomCategory extends Model
{

    use AutoCreatedUpdated;


    public function roomSingleImg()
    {

        return $this->hasOne(RoomPhotos::class, 'category_id', 'id')->orderBy('id', 'DESC');
    }

    public function roomMultipleImg()
    {

        return $this->hasMany(RoomPhotos::class, 'category_id', 'id')->latest();
    }

    public function roomAminities()
    {
        return $this->hasMany(Aminities::class, 'id', 'room_aminities');
    }

    public function roomCategory()
    {
        return $this->hasMany(Rooms::class, 'room_category', 'id');
    }

    public function roomPrices()
    {
        return $this->hasMany(RoomPrice::class, 'room_category_id', 'id');
    }


    public function rooms()
    {
        return $this->hasMany(Rooms::class, 'room_category', 'id');
    }


    public static function roomName()
    {

        return RoomCategory::pluck('name', 'id');

    }
}
