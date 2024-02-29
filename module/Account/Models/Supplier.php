<?php


namespace Module\Account\Models;


use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Supplier extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'acc_suppliers';

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class, 'id', 'supplier_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
