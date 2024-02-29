<?php

namespace Module\BanquetHall\Models;

use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetAmenitie extends Model
{
    // use HasFactory, AutoCreatedUpdatedWithCompany;
    use AutoCreatedUpdated;

    protected $table = 'banquet_amenities';
    protected $guarded = [];
}
