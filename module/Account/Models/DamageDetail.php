<?php


namespace Module\Account\Models;

use Module\PosErp\Models\Damage;

class DamageDetail extends Model
{
    protected $table = 'acc_damage_details';

    
    public function damage()
    {
        return $this->belongsTo(Damage::class, 'purchase_return_id', 'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }



    public function pos_stocks()
    {
        return $this->hasMany(ProductStockDetail::class, 'source_id', 'id')->where('type', 'Account Product Damage');
    }
}
