<?php

namespace Module\BanquetHall\Models;

use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetCategory extends Model
{
    use HasFactory;
    use AutoCreatedUpdated;

    protected $guarded = [];



    public function roomSingleImg()
    {

        return $this->hasOne(BanquetRoomPhoto::class, 'category_id', 'id')->orderBy('id', 'DESC');
    }

    public function roomMultipleImg()
    {

        return $this->hasMany(BanquetRoomPhoto::class, 'category_id', 'id')->latest();
    }

    public function roomAminities()
    {
        return $this->hasMany(BanquetAmenitie::class, 'id', 'room_aminities');
    }

    public function roomCategory()
    {
        return $this->hasMany(BanquetRoom::class, 'room_category', 'id');
    }

    public function roomPrices()
    {
        return $this->hasMany(RoomPrice::class, 'room_category_id', 'id');
    }


    public function rooms()
    {
        return $this->hasMany(BanquetRoom::class, 'room_category', 'id');
    }


    public static function roomName()
    {

        return BanquetCategory::pluck('name', 'id');

    }

}
