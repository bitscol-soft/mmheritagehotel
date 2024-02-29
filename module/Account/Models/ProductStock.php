<?php

namespace Module\Account\Models;

use App\Model;
use App\Models\Company;

class ProductStock extends Model
{

    protected $table = 'rmreports';

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
