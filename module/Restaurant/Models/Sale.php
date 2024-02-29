<?php

namespace Module\Restaurant\Models;

use App\Models\User;
use App\Models\Company;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Booking;
use Module\Restaurant\Models\Model;
use Module\Account\Models\Transaction;
use Module\Restaurant\Models\SaleItem;
use Module\Hotel\Models\HotelTransection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Module\Hotel\Models\HotelTransactionLedger;

class Sale extends Model
{

    use SoftDeletes;


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






    // protected $casts = [
    //     'date'  => 'date'
    // ];

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


    public function barTransactions()
    {
        return $this->hasMany(HotelTransection::class, 'source_id')->where('source_type', 'Bar Sale');
    }


    public function RstTransactions()
    {
        return $this->hasMany(HotelTransection::class, 'source_id')->whereIn('source_type', ['Resturent Sale', 'Restaurant Sale']);
    }


    public function RstTransaction()
    {
        return $this->hasOne(HotelTransection::class, 'source_id')->whereIn('source_type', ['Resturent Sale', 'Restaurant Sale']);
    }



    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }



    public function table()
    {
        return $this->belongsTo(RstTableManage::class, 'table_id');
    }


    public function guest()
    {
        return $this->belongsTo(Guest::class, 'hotel_guest_id');
    }


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'hotel_booking_id');
    }
}
