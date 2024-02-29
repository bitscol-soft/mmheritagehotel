<?php

namespace Module\Restaurant\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Carbon\Carbon;
use Module\Restaurant\Models\Model;

class PurchaseDetails extends Model
{
    // use AutoCreatedUpdatedWithCompany;




    protected $table = 'rst_purchase_details';







    public function product()
    {
        return $this->belongsTo(Product::class);
    }








    // public function setExpiryDateAttribute($date)
    // {
    //     dd(request()->expiry_date[1]);
    //     $this->attributes['expiry_date'] = Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
    // }
}
