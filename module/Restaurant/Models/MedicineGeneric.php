<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Restaurant\Models\Model;

class MedicineGeneric extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_medicine_generics';
}
