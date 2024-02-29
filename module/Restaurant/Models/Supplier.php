<?php

namespace Module\Restaurant\Models;

use Module\Restaurant\Models\Model;
use Illuminate\Support\Facades\App;

class Supplier extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function ($model) {
                $model->fill([
                    'created_by' => auth()->id(),
                    'company_id' => auth()->user()->company->id,
                    'updated_by' => auth()->id(),
                ]);
            });

            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id()
                ]);
            });
        }
    }

    protected $table = 'rst_suppliers';



    public function scopeSuppliers($q)
    {
        return $q->where('company_id', auth()->user()->company_id);
    }
}
