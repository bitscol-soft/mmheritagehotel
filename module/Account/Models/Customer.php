<?php


namespace Module\Account\Models;

use App\Models\User;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'acc_customers';

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function sales(){
        return $this->hasMany(Sale::class, 'customer_id');
    }


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
