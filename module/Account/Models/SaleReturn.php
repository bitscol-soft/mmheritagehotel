<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class SaleReturn extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'acc_sale_returns';



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }



    public function return_details()
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id');
    }



    public function exchange_details()
    {
        return $this->hasMany(SaleExchangeDetail::class, 'sale_return_id');
    }


    public function sale()
    {
        return $this->hasMany(Sale::class, 'sale_id');
    }

    

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
