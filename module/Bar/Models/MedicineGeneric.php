<?php

namespace Module\Bar\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Bar\Models\Model;

class MedicineGeneric extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_medicine_generics';
}
