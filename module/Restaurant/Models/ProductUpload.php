<?php

namespace Module\Restaurant\Models;

use App\Model;

class ProductUpload extends Model
{
    protected $table = 'rst_product_uploads';



    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }


    public function unit(){

        return $this->belongsTo(ProductUnit::class, 'unit_id');
    }

    public function pack_unit(){

        return $this->belongsTo(ProductUnit::class, 'pack_unit_id');
    }
}
