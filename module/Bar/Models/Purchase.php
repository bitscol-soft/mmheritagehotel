<?php

namespace Module\Bar\Models;

use App\Models\Company;
use Module\Bar\Models\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Hospital\Models\HospitalTransaction;

class Purchase extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_purchases';


    public function purchase_details()
    {
        return $this->hasMany(PurchaseDetails::class);
    }






    public function company()
    {
        return $this->belongsTo(Company::class);
    }










    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }









    public function transactions()
    {
        return $this->morphMany(HospitalTransaction::class, 'transactionable');
    }
}
