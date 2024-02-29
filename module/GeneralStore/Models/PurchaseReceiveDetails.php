<?php

namespace Module\GeneralStore\Models;

use App\Model;
use Module\GeneralStore\Models\Item;
use App\Traits\AutoCreatedUpdated;

class PurchaseReceiveDetails extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase_receive()
    {
        return $this->belongsTo(PurchaseReceive::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function is_in_stock()
    {
        return $this->hasMany(StockTracking::class, 'tracking_id', 'id')->where('type', 'receive');
    }

}
