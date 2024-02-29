<?php

namespace App\Models;

use App\Models\Currency;
use App\Traits\AutoCreatedUpdated;
use Module\Hotel\Models\HotelTransection;

class CurrencyConversion extends Model
{
    use AutoCreatedUpdated;

    protected $guarded = [];

    protected $table = 'currency_conversions';


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function hotelTransaction()
    {
        return $this->hasMany(HotelTransection::class, 'currency_conversion_id');
    }

}
