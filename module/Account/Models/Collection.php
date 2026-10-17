<?php


namespace Module\Account\Models;


use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Collection extends Model
{
    protected $table = 'acc_collections';

    use AutoCreatedUpdatedWithCompany;


    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
