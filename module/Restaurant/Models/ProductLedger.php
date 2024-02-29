<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class ProductLedger extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_ledgers';


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
