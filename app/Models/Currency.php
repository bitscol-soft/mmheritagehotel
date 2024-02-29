<?php

namespace App\Models;

use App\Models\CurrencyConversion;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = [];

    protected $table = 'currencies';

    public function currency_conversions()
    {
        return $this->hasMany(CurrencyConversion::class);
    }

    public static function roomName()
    {
        return Currency::pluck('name', 'id');
    }

}
