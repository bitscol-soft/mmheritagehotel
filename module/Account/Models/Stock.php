<?php

namespace Module\Account\Models;

use App\Model;
use App\Traits\AutoCreatedUpdatedWithCompany;

class Stock extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'acc_stocks';

    protected $guarded = [];

    public function stockable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->morphTo(Product::class);
    }

    public function purchaseDetails()
    {
        return $this->morphTo(PurchaseDetail::class);
    }

    public function saleDetails()
    {
        return $this->morphTo(SaleDetail::class);
    }
}
