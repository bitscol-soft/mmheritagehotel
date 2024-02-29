<?php

namespace Module\Bar\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class Stock extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_stocks';


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }


    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id', 'id');
    }
}
