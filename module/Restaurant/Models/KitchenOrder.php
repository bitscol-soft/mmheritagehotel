<?php

namespace Module\Restaurant\Models;

use Module\Restaurant\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KitchenOrder extends Model
{
    use HasFactory;

    public function order_items(){
        return $this->hasMany(KitchenOrderDetail::class,'order_id');
    }

    public function sale(){
        return $this->hasOne(Sale::class,'id', 'sale_id');
    }
}
