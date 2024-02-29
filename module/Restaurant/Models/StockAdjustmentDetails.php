<?php

namespace Module\Restaurant\Models;

use App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

class StockAdjustmentDetails extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
        // return $this->HasMany(Product::class);
    }
}
