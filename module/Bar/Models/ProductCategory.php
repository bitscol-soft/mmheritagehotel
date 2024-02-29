<?php

namespace Module\Bar\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Bar\Models\Model;

class ProductCategory extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_product_categories';







    /*
     |--------------------------------------------------------------------------
     | PARENT CATEGORIES METHOD
     |--------------------------------------------------------------------------
    */
    public function parentCategories()
    {
        return  $this->hasMany(ProductCategory::class, 'id', 'parent_id')
                ->with('parentCategories')->select('id', 'name', 'parent_id');
    }





    /*
     |--------------------------------------------------------------------------
     | CHILD CATEGORIES METHOD
     |--------------------------------------------------------------------------
    */
    public function childCategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id', 'id')
                ->with('childCategories')
                ->orderBy('id', 'asc')
                ->select('id', 'parent_id', 'name');
    }


    
}
