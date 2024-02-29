<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RstProduction extends Model
{
    use HasFactory, AutoCreatedUpdated;

    public function metrial_details()
    {
        return $this->hasMany(RstMetrialDetails::class, 'production_id', 'id');
    }


    public function fgood_details()
    {
        return $this->hasMany(RstFinishGoodDetails::class, 'production_id', 'id');
    }

}
