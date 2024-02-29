<?php

namespace Module\Hotel\Models;

use App\Traits\AutoCreatedUpdated;
use Module\Hotel\Models\BookingDetails;

class Rooms extends Model
{

    use AutoCreatedUpdated;

    public static function roomNumber()
    {

        return Rooms::pluck('room_number', 'id');

    }



    public function roomCategory()
    {
        return $this->belongsTo(RoomCategory::class, 'room_category');
    }

    public function booking_details()
    {
        return $this->hasMany(BookingDetails::class,'room_id');
    }


    public function booking_details_many()
    {
        return $this->belongsToMany(Booking::class, 'booking_details', 'booking_id', 'room_id');
    }

    public function booking_detail()
    {
        return $this->hasOne(BookingDetails::class, 'room_id');
    }


    public function booking_dates()
    {
        return $this->hasMany(BookingDateDetails::class, 'room_id');
    }


    public function isBooking($id, $date)
    {
       return $this->booking_dates()->where('date', $date)->first();
    }


    public function bookingCart()
    {
        return $this->hasOne(BookingCart::class, 'room_id', 'id');
    }

    public function roomLog()
    {
        return $this->belongsTo(RoomLog::class, 'room_id', 'id');
    }
}
