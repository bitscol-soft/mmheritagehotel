<?php

namespace Module\Restaurant\Models;
use Module\Restaurant\Models\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class KitchenOrderDetail extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}

