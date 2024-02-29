<?php

namespace Module\Hotel\Models;

use App\Model;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Module\Account\Models\CustomerLedger;
use Module\CRM\Models\CRMCustomer;

class Guest extends Model
{
    use HasFactory;

    protected $table = 'hotel_guest';

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public static function guestName()
    {

        $data = Guest::get();
        $response = [];
        if ($data) {
            foreach ($data as $key => $value) {
                $response[$value->id] = $value->name;
            }
        }
        return $response;
    }

    public function booking()
    {
        return $this->belongsTo(BookingDetails::class, 'booking_id', 'booking_id');
    }

    public function bookingList()
    {
        return $this->hasOne(BookingDetails::class, 'booking_id', 'booking_id');
    }



    public function members()
    {
        return $this->hasMany(BookingMemberDetail::class, 'guest_id');
    }



    public function company()
    {
        return $this->belongsTo(CRMCustomer::class,'company_id');
    }

    public function customerLedger()
    {
        return $this->hasMany(CustomerLedger::class, 'hotel_guest_id');
    }



}
