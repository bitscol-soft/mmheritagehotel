<?php

namespace Module\GeneralStore\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Support\Arr;

class PurchaseDetails extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function totalItemReceivedQuantity()
    {
        return Arr::get($this->totalItemReceived, 'sum_quantity', 0);
    }

    public function purchase_receive_details(){
        return $this->hasMany(PurchaseReceiveDetails::class);
    }

    public  function itemReceived(){
        return $this->hasOne(PurchaseReceiveDetails::class)->groupBy('purchase_details_id')->selectRaw('sum(quantity) as total, purchase_details_id');
    }
}
