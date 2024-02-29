<?php

namespace Module\GeneralStore\Models;

use Module\Garments\Models\Commercial\BBLC\BBLC;
use App\Models\Country;
use App\Models\Group;
use Module\Garments\Models\Inventory\ArpWorkOrder;
use Module\Garments\Models\Inventory\KnitYarnWorkOrder;
use Module\Garments\Models\Inventory\SubcontractWorkOrder;
use Module\Garments\Models\Inventory\SupplierPi;
use Module\Garments\Models\Inventory\SweaterYarnWorkOrder;
use Module\Garments\Models\Inventory\WovenFabricWorkOrder;
use App\Models\Payment\CashPayment;
use App\Models\SupplierType;
use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use AutoCreatedUpdated;

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function supplier_type(): BelongsTo
    {
        return $this->belongsTo(SupplierType::class);
    }

    public function supplier_pis(): HasMany
    {
        return $this->hasMany(SupplierPi::class);
    }

    public function bblcs(): HasMany
    {
        return $this->hasMany(BBLC::class);
    }

    public function regularBBLCs()
    {
        return $this->hasMany(BBLC::class, 'supplier_id')->where('is_regular', 1);
    }

    public function irregularBBLCs()
    {
        return $this->hasMany(BBLC::class, 'supplier_id')->where('is_regular', 0);
    }

    public function cashPayments(): HasMany
    {
        return $this->hasMany(CashPayment::class);
    }

    public function arpWorkOrders(): HasMany
    {
        return $this->hasMany(ArpWorkOrder::class);
    }

    public function sweaterYarnWorkOrders(): HasMany
    {
        return $this->hasMany(SweaterYarnWorkOrder::class);
    }

    public function knitYarnWorkOrders(): HasMany
    {
        return $this->hasMany(KnitYarnWorkOrder::class);
    }

    public function subcontractWorkOrders(): HasMany
    {
        return $this->hasMany(SubcontractWorkOrder::class);
    }

    public function wovenFabricWorkOrders(): HasMany
    {
        return $this->hasMany(WovenFabricWorkOrder::class);
    }
}
