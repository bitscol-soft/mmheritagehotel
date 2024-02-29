<?php

namespace Module\Hotel\Models;

use App\Traits\AutoCreatedUpdated;
use Module\Hotel\Models\BookingGuestDetail;

class BookingDetails extends Model
{

    use AutoCreatedUpdated;



    public function guest_details()
    {
        return $this->hasMany(BookingGuestDetail::class, 'booking_detail_id');
    }

    public function booking_members()
    {
        return $this->hasMany(BookingMemberDetail::class, 'booking_id');
    }


    public function roomCategory()
    {
        return $this->hasOne(RoomCategory::class, 'id', 'category_id');
    }


    // public function bookingAdjust($bookingId, $fromRoomId)
    // {
    //     return $this->hasOne(BookingAdjust::class, 'id', 'booking_detail_id')->where('booking_id', $bookingId)->where('from_room_id', $fromRoomId);
    // }

    public function bookingAdjust()
    {
        return $this->hasOne(BookingAdjust::class, 'booking_id' , 'booking_id');
    }

    public function roomNumber()
    {
        return $this->hasOne(Rooms::class, 'id', 'room_id');
    }

    public function bookingInfo()
    {
        return $this->hasOne(Booking::class, 'id', 'booking_id');
    }

    public function bookingTransection()
    {
        return $this->hasOne(HotelTransection::class, 'source_id', 'booking_id')->where('source_type', 'Booking');
    }
}
