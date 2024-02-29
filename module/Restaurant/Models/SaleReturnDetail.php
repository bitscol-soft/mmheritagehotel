<?php

namespace Module\Restaurant\Models;

use App\Model;
use Module\Restaurant\Models\Product;
use Module\Restaurant\Models\SaleReturn;

class SaleReturnDetail extends Model
{
    protected $table = 'rst_sale_return_details';



    public function sale_return()
    {
        return $this->belongsTo(SaleReturn::class, 'sale__return_id');
    }



    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
