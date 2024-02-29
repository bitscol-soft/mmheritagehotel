<?php

namespace Module\Hotel\Models;

use App\Model;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingNote extends Model
{
    use HasFactory;

    protected $table = 'booking_notes';


    /*
     |--------------------------------------------------------------------------
     | GET TABLE NAME
     |--------------------------------------------------------------------------
    */
    public static function getTableName()
    {
        return with(new static)->getTable();
    }



}
