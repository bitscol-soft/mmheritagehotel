<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class HotelCustomerLedger extends Model
{

    use AutoCreatedUpdated;


    protected $table = 'hotel_customer_ledgers';

    protected $guarded = [];
    

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'hotel_guest_id');
    }

}
