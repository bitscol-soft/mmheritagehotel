<?php

namespace Module\Bar\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Bar\Models\Model;

class Manufacturer extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_manufacturers';
}
