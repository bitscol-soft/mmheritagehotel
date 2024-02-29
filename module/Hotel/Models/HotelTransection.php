<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Models\CurrencyConversion;
use App\Traits\AutoCreatedUpdated;
use Module\Restaurant\Models\Sale;
use Module\Account\Models\Transaction;
use Module\Bar\Models\Sale as BarSale;
use Module\Restaurant\Models\Purchase;
use Module\Restaurant\Models\SaleReturn;
use Module\BanquetHall\Models\BanquetBooking;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Module\HotelService\Models\Service\HotelServiceSale;

Relation::morphMap([
    'Hotel Service Sale'        => HotelServiceSale::class,
    'Booking'                   => Booking::class,
    'Hall Booking'              => BanquetBooking::class,
    'Booking Adjust'            => BookingAdjust::class,
    'Restaurant Sale'           => Sale::class,
    'Resturent Sale'            => Sale::class,
    'Purchase'                  => Purchase::class,
    'Bar Sale'                  => BarSale::class,
    'Restaurant Sale Return'    => SaleReturn::class,
]);

class HotelTransection extends Model
{

    use AutoCreatedUpdated;

    protected $table = 'hotel_account_transactions';




    public function source()
    {
        return $this->morphTo();
    }





    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }




    public function account()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }



    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }


    public function transaction_ledgers()
    {
        return $this->hasMany(HotelTransactionLedger::class, 'hotel_transaction_id');
    }


    public function night_audit_transaction_ledgers()
    {
        return $this->hasMany(HotelTransactionLedger::class, 'transaction_ledger_id', 'id');
    }



    public function night_audits()
    {
        return $this->hasMany(NightAuditDetail::class, 'transaction_id');
    }



    public function currencyConversion()
    {
        return $this->belongsTo(CurrencyConversion::class, 'currency_conversion_id');
    }


    public function extraCharge_ledger()
    {
        return $this->hasOne(HotelTransactionLedger::class, 'hotel_transaction_id')->where('remarks', 'Extra Charge');
    }


    public function rstSale()
    {
        return $this->belongsTo(Sale::class, 'source_id');
    }


     public function HotelSale()
    {
        return $this->belongsTo(Booking::class, 'source_id');
    }



    public function nightClosingLedger()
    {
        return $this->hasMany(NightAuditTransaction::class, 'transaction_id');
    }

}
