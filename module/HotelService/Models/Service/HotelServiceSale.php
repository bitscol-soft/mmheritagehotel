<?php

namespace Module\HotelService\Models\Service;

use App\Model;
use App\Models\User;
use App\Models\Company;
use Module\Account\Models\Transaction;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\HotelTransection;
use Module\HotelService\Models\Service\HotelServiceSaleItem;

class HotelServiceSale extends Model
{

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();
            $model->fill([
                'company_id' => $user->company_id,
                'created_by' => $user->id,
            ]);
        });

        static::updating(function ($model) {
            $model->fill([
                'updated_by' => auth()->id()
            ]);
        });

        static::deleting(function ($model) {

            $model->saleItems()->delete();
            // $model->transactions()->delete();
            // $model->ledgers()->delete();
        });
    }





    public function saleItems()
    {
        return $this->hasMany(HotelServiceSaleItem::class, 'hotel_service_sale_id', 'id');
    }


    public function details()
    {
        return $this->hasMany(HotelServiceSaleItem::class, 'hotel_service_sale_id', 'id');
    }



    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function hotel_guest()
    {
        return $this->belongsTo(Guest::class, 'hotel_guest_id');
    }







    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }



    public function hotel_transactions()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }



    public function transaction()
    {
        return $this->morphOne(HotelTransection::class, 'source');
    }



    public function transection_ledgers()
    {
        return $this->morphMany(HotelTransactionLedger::class, 'source');
    }


    public function getServiceNames()
    {
        $names = [];
        foreach ($this->saleItems as $item) {
            $names[] = optional($item->service)->name;
        }
        return implode(', ', $names);
    }
}
