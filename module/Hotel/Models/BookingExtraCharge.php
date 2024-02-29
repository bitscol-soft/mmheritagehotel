<?php

namespace Module\Hotel\Models;

use App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingExtraCharge extends Model
{
    use HasFactory;

    protected $table = 'booking_extra_charges';

    
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

}
