<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;
use Module\Production\Models\RequsitionPurchase;

Relation::morphMap([
    'Requsition Purchase' => RequsitionPurchase::class,
]);


class Purchase extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_purchases'; 




    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id', 'id');
    }

    

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    


    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }



    public function sourceable()
    {
        return $this->morphTo();
    }
}
