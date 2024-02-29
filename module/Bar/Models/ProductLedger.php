<?php

namespace Module\Bar\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class ProductLedger extends Model
{
    use AutoCreatedUpdatedWithCompany;
    
    protected $table = 'rst_product_ledgers';
}
