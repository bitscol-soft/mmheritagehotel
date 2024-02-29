<?php

namespace Module\Bar\Models;

use Module\Bar\Models\Model;

class SaleItem extends Model
{

    protected $table = 'rst_sale_items';




    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }
    public function small_unit()
    {
        return $this->belongsTo(ProductUnit::class, 'small_unit_id');
    }
}
