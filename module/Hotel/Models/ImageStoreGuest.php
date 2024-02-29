<?php

namespace Module\Hotel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;

class ImageStoreGuest extends Model
{
    use HasFactory;

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function guest()
    {
        return $this->hasMany(Guest::class, 'guest_id');
    }
}
