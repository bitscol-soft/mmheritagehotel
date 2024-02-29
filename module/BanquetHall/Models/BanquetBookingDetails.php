<?php

namespace Module\BanquetHall\Models;

use App\Model;

use App\Traits\AutoCreatedUpdated;
use Module\BanquetHall\Models\BanquetRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetBookingDetails extends Model
{
    use HasFactory, AutoCreatedUpdated;


    public function hall()
    {
        return $this->belongsTo(BanquetRoom::class,'halls_id');
    }


}
