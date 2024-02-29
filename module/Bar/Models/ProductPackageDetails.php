<?php

namespace Module\Bar\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackageDetails extends Model
{

    protected $table = 'rst_product_package_details';
    protected $guarded = [];



    public function product_package(){
        return $this->belongsToMany(ProductPackage::class, 'package_id', 'id');
    }


    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
