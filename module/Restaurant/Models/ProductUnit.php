<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Restaurant\Models\Model;

class ProductUnit extends Model
{
    use AutoCreatedUpdatedWithCompany;


    protected $table = 'rst_product_units';

    
}
