<?php

namespace Module\Hotel\Models;

use App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Module\Restaurant\Models\Sale;

class CardAuthorizedInfo extends Model
{
    use HasFactory;

    protected $table = 'card_authorized_information';

    protected $guarded = [];

    public function booking(){

        return $this->belongsTo(Booking::class, 'id', 'booking_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'booking_id', 'booking_id');
    }
}
