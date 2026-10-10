<?php

namespace Module\Restaurant\Models;

use Module\Restaurant\Models\Model;
use Illuminate\Support\Facades\App;

class Product extends Model
{

    protected $table = 'rst_products';

    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function ($model) {
                $model->fill([
                    // 'opening_quantity'  => request()->opening_quantity ?: 0,
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


    public function batch(){

        return $this->belongsTo(ProductBatch::class, 'batch_id');
    }


    public function stock(){

        return $this->hasMany(Stock::class, 'product_id');
    }


    public function stocks(){

        return $this->hasMany(Stock::class, 'product_id');
    }


    public function rstStock(){

        return $this->hasOne(Stock::class, 'product_id')->where('is_bar', 0);
    }



    public function stock_ledgers(){

        return $this->hasMany(ProductLedger::class, 'product_id');
    }


    public function saleItems(){

        return $this->hasMany(SaleItem::class, 'product_id');
    }


    public function getProductIdAttribute(){

        return "#P-" . str_pad($this->id, 5, '0', 0);
    }



    public function ScopeNotPackage($q){

        return $q->where('package_id', null);
    }

    public function ScopePackage($q){

        return $q->where('package_id', '!=', null);
    }

    public function ScopeMaterial($q){

        return $q->where('is_matrial', '!=', null);
    }

    public function ScopeNotMaterial($q){

        return $q->where('is_matrial', null);
    }


    public function ScopeNotBar($q){

        return $q->where('is_bar', 0);
    }


    public function wholesaleUnit()
    {
        return $this->belongsTo(ProductUnit::class, 'wholesale_unit_id', 'id');
    }
}
