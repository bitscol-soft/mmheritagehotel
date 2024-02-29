<?php

namespace Module\BanquetHall\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Module\Restaurant\Models\Product;

class BanquetProductDetails extends Model
{
    use HasFactory, AutoCreatedUpdated;


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }




}
