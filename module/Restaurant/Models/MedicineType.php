<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Restaurant\Models\Model;

class MedicineType extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_medicine_types';
}
