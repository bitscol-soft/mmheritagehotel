<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class PurchaseReturn extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_purchase_returns';



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }



    public function return_details()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id');
    }



    public function exchange_details()
    {
        return $this->hasMany(PurchaseExchangeDetail::class, 'purchase_return_id');
    }


    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'purchase_id');
    }

    

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
