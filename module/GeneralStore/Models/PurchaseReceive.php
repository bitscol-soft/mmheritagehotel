<?php

namespace Module\GeneralStore\Models;

use App\Models\Company;
use App\Model;
use Module\GeneralStore\Models\Purchase;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;

class PurchaseReceive extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;


    public function purchase_receive_details()
    {
        return $this->hasMany(PurchaseReceiveDetails::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }


   public function totalQuantity()
   {
       return $this->hasMany(PurchaseReceiveDetails::class)
           ->selectRaw('SUM(quantity) as totalReceived, SUM(remaining_quantity) as totalRemaining, purchase_receive_id')
           ->groupBy('purchase_receive_id');
   }

    public function created_user()
    {

        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {

        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
