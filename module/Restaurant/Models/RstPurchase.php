<?php

namespace Module\Restaurant\Models;

use App\Models\Company;
use Illuminate\Support\Arr;
// use Illuminate\Database\Eloquent\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Support\Facades\Auth;
use Module\Restaurant\Models\Supplier;
use App\Traits\AutoCreatedUpdatedWithCompany;

class RstPurchase extends Model
{
    use AutoCreatedUpdatedWithCompany;

    protected $table = 'rst_material_purchase';



    public function purchase_details()
    {
        return $this->hasMany(RstPurchaseDetails::class, 'purchase_id', 'id');
    }



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }














    public function scopePurchases($q)
    {
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }




    public function item_unit()
    {
        return $this->belongsTo(RestMaterialUnit::class);
    }

    // public function purchase_receives()
    // {
    //     return $this->hasMany(PurchaseReceive::class);
    // }

    public function last_receive()
    {
        return $this->hasMany(PurchaseReceive::class);
    }

//    public function last_receive()
//    {
//        return $this->hasOne($this->purchase_receives()->orderByDesc('id')->first());
//    }


    public function purchaseReceiveCount()
    {
        return $this->hasMany(PurchaseReceive::class)->count();
    }

    public function purchaseReceive()
    {
        return $this->hasOne(PurchaseReceive::class)->selectRaw('sum(quantity) as sumReceive, purchase_id')->groupBy('purchase_id');
    }

    public function purchaseReceiveQuantity()
    {
        return Arr::get($this->purchaseReceive, 'sumReceive', 0);
    }

    public function purchaseReceivedItemQuantity()
    {
        return Arr::get($this->purchaseReceivedItem()->first(), 'sum_quantity', 0);
    }

    public function purchase_details_sum(){
//        return $this->hasMany(PurchaseDetails::class)->pluck('itemReceived');
        return $this->hasOne(RstPurchaseDetails::class)->groupBy('purchase_id')->selectRaw('sum(itemReceived) as total, purchase_id');
    }


    // method for purchase notification
    public function task_notifications()
    {
        // return $this->morphMany('Module\HRM\Models\News\TaskNotification', 'taskable');
        return $this->morphMany('Module\HRM\Models\News\TaskNotification', 'taskable');
    }

}
