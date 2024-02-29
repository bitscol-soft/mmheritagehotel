<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use App\Model;

class Stock extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_stocks';




    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public function batch()
    {
        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }


    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id', 'id');
    }



    public function generic()
    {
        return $this->belongsTo(MedicineGeneric::class, 'generic_id', 'id');
    }



    public function medicineType()
    {
        return $this->belongsTo(MedicineType::class, 'medicine_type_id', 'id');
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
