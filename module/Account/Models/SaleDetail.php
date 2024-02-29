<?php


namespace Module\Account\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class SaleDetail extends Model
{
    protected $table = 'acc_sale_details';

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function sale_returns()
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_detail_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


    public function stock()
    {
        return $this->morphOne(Stock::class, 'stockable');
    }


    public function pos_stocks()
    {

        return $this->hasMany(ProductStockDetail::class, 'source_id', 'id')->where('type', 'Account Purchase');
    }
}
