<?php

namespace Module\Restaurant\Models;

use App\Models\Company;
use Module\Restaurant\Models\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;
// use Module\Hospital\Models\HospitalTransaction;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Account\Models\Transaction;

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



    public function hotel_transactions()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }


    
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }



    public function transaction()
    {
        return $this->morphOne(HotelTransection::class, 'source');
    }



    public function transection_ledgers()
    {
        return $this->morphMany(HotelTransactionLedger::class, 'source');
    }
}
