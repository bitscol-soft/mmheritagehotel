<?php

namespace Module\Bar\Models;

use App\Model as BaseModel;

class Model extends BaseModel
{
    /* ------------------------------------------
     |  Properties
     | ------------------------------------------
     */

    // protected $prefix = 'rst_';



    
    public static function query()
    {
        if (!setting('mother_inventory')) {
            
            return static::where('is_bar', 1);
        }
        return static::whereIn('is_bar', [0,1]);
    }




    protected static function booted()
    {
        if (with(new static)->getTable() != 'rst_sale_items') {
            static::creating(function ($model) {
                $model->fill([
                    'is_bar'    => request('bar_or_restaurant') ? request('bar_or_restaurant') : 1,
                ]);
            });
        };
        
    }
}
