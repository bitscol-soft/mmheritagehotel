<?php


namespace Module\Account\Models;

use Module\PosErp\Models\PurchaseReturn;

class PurchaseExchangeDetail extends Model
{
    protected $table = 'acc_purchase_exchange_details';


    
    public function purchase_return()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



    public function pos_stocks()
    {
        return $this->hasMany(ProductStockDetail::class, 'source_id', 'id')->where('type', 'Account Purchase Exchange');
    }
}
