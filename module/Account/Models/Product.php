<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdatedWithCompany;
use Module\Account\Models\PurchaseDetail;
use Module\Production\Models\Factory;
use Module\Production\Models\RawMaterialsDetails;
use Module\Production\Models\RequsitionPurchaseDetails;
use Module\Production\Models\RequsitionPurchaseReceiveDetails;
use Module\Production\Models\RMStockInHand;

class Product extends Model
{
    use AutoCreatedUpdatedWithCompany;


    public function scopeAccountProduct($query)
    {
        $query->whereIn('product_type', ['0', 'account_prod']);
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'product_id', 'id');
    }

    public function rawMaterialsDetails()
    {
        return $this->hasMany(RawMaterialsDetails::class, 'product_id', 'id');
    }

    public function requsitionPurchaseDetails()
    {
        return $this->hasMany(RequsitionPurchaseDetails::class, 'product_id');
    }

    public function requsitionPurchaseReceiveDetails()
    {
        return $this->hasMany(RequsitionPurchaseReceiveDetails::class, 'product_id')->orderBy('id', 'DESC');
    }

    public function product_stock()
    {
        return $this->hasOne(ProductStock::class, 'product_id');
    }

    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    public function stock()
    {
        return $this->morphOne(Stock::class, 'stockable');
    }


    public function scock_summeries()
    {
        return $this->hasMany(StockSummary::class, 'product_id');
    }


    public function scopeUserCompanies($query)
    {
        $query->when(auth()->id() != 1, function($q) {
            $q->whereIn('company_id', Company::userCompanyId());
        });
    }
}
