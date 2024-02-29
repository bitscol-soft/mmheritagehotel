<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Models\Company;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockAdjustment extends Model
{
    use HasFactory, AutoCreatedUpdated;


    public function adjustment_details()
    {
        return $this->hasMany(StockAdjustmentDetails::class);
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
