<?php

namespace Module\GeneralStore\Models;

use App\Model;
use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class Item extends Model
{
    // fillup created and updated fields, and add created_user, updated_user method
    use AutoCreatedUpdated;

    public function item_unit()
    {
        return $this->belongsTo(ItemUnit::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //  return items only permitted companies
    public function scopeItems($q)
    {
        return $q->whereIn('company_id', Company::userCompanyId());
    }

    public function goods_requisition()
    {
        return $this->hasMany(GoodsRequisitionDetails::class);
    }

    public function purchase_detail()
    {
        return $this->hasMany(PurchaseDetails::class);
    }


}
