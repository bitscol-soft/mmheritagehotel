<?php

namespace Module\GeneralStore\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;


class GoodsRequisitionDetails extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;

    public function goods_requisition()
    {
        return $this->belongsTo(GoodsRequisition::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stock_track()
    {
        return $this->belongsTo(StockTracking::class);
    }
}
