<?php

namespace Module\Bar\Models;

use App\Models\User;
use App\Models\Company;
use Module\Account\Models\Transaction;
use Module\Bar\Models\Model;
use Module\Hotel\Models\Guest;
use Module\Bar\Models\SaleItem;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;

class Sale extends Model
{


    protected $table = 'rst_sales';


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();

            $model->fill([
                'company_id' => $user->company_id,
                'created_by' => $user->id,
                // 'invoice_no' => (new InvoiceNumberService)->getInvoiceNumber('Pharmacy Sale', request()->date),
            ]);
        });

        static::updating(function ($model) {
            $model->fill([
                'updated_by' => auth()->id()
            ]);
        });

        // static::deleting(function ($model) {

        //     $model->items()->delete();
        //     $model->transactions()->delete();
        // });
    }

    /**
     * Using for morph relation
     */
    public function details()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }


    public function items()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }



    public function return_items()
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    public function table()
    {
        return $this->belongsTo(RstTableManage::class, 'table_id');
    }


    public function guest()
    {
        return $this->belongsTo(Guest::class, 'hotel_guest_id');
    }

    public function guestInfo()
    {
        return $this->hasOne(Guest::class, 'id', 'hotel_guest_id');
    }


    public function hotel_transactions()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }


    public function transaction_ledgers()
    {
        return $this->morphMany(HotelTransactionLedger::class, 'source');
    }

    // public function hotel_transaction_ledgers()
    // {
    //     return $this->hasMany(HotelTransactionLedger::class, 'source_id');
    // }


    public function RstTransactions()
    {
        return $this->hasMany(HotelTransection::class, 'source_id')->where('source_type', 'Bar Sale');
    }


    public function barTransaction()
    {
        return $this->hasOne(HotelTransection::class, 'source_id')->where('source_type', 'Bar Sale');
    }



    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
