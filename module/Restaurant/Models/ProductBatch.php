<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class ProductBatch extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_batches';
}
