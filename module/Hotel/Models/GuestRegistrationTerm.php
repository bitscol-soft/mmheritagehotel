<?php

namespace Module\Hotel\Models;

use App\Model;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuestRegistrationTerm extends Model
{
    use HasFactory;

    protected $table = 'hotel_guest_registration_terms';

}
