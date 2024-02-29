<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sale extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_sales';



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }



    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
