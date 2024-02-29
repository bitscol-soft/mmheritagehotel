<?php

namespace Module\Restaurant\Models;

use Module\Restaurant\Models\Model;

class SaleItem extends Model
{

    protected $table = 'rst_sale_items';




    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }


    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productMaterials()
    {
        return $this->hasMany(PrductMetrial::class, 'product_id', 'product_id');
    }
}
