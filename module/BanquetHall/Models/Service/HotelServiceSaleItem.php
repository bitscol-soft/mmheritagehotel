<?php

namespace Module\HotelService\Models\Service;

use App\Model;
use Module\HotelService\Models\Service\HotelService;

class HotelServiceSaleItem extends Model
{

    protected $dates = [
        'delivered_at'
    ];


    public function service()
    {
        return $this->belongsTo(HotelService::class, 'hotel_service_id');
    }
}
