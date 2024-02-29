<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Restaurant\Models\Model;

class Manufacturer extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_manufacturers';
}
