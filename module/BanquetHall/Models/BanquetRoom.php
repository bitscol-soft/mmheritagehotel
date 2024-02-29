<?php

namespace Module\BanquetHall\Models;

use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetRoom extends Model
{
    use AutoCreatedUpdated;

    protected $guarded = [];


    public function category(){

        return $this->hasOne(BanquetCategory::class, 'id', 'hall_category');
    }

    public static function roomNumber()
    {

        return BanquetRoom::pluck('room_number', 'id');

    }

}
