<?php

namespace Module\Bar\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ProductPackage extends Model
{

    protected $table = 'rst_product_package';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function ($model) {
                $model->fill([
                    'created_by'        => auth()->id(),
                    'company_id'        => optional(optional(auth()->user())->company)->id ?? optional(auth()->user())->company_id,
                ]);
            });

            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id()
                ]);
            });
        }
    }



    public function product_package_details(){
        return $this->hasMany(ProductPackageDetails::class, 'package_id', 'id');
    }


    public function package_product(){
        return $this->hasOne(Product::class, 'package_id', 'id');
    }


}
