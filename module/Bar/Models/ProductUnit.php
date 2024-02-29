<?php

namespace Module\Bar\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Bar\Models\Model;

class ProductUnit extends Model
{
    use AutoCreatedUpdatedWithCompany;


    protected $table = 'rst_product_units';
}
