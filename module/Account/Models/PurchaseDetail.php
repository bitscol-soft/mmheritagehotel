<?php


namespace Module\Account\Models;


class PurchaseDetail extends Model
{
    protected $table = 'acc_purchase_details';

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
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
