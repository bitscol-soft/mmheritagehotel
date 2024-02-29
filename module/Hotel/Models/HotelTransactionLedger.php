<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;
use Module\Restaurant\Models\Sale;
use Module\Bar\Models\Sale as BarSale;
use Module\Restaurant\Models\SaleReturn;
use Module\BanquetHall\Models\BanquetBooking;
use Illuminate\Database\Eloquent\Relations\Relation;
use Module\HotelService\Models\Service\HotelServiceSale;

Relation::morphMap([
    'Hotel Service Sale'        => HotelServiceSale::class,
    'Booking'                   => Booking::class,
    'Hall Booking'              => BanquetBooking::class,
    'Booking Adjust'            => BookingAdjust::class,
    'Restaurant Sale'           => Sale::class,
    'Bar Sale'                  => BarSale::class,
    'Restaurant Sale Return'    => SaleReturn::class,
]);

class HotelTransactionLedger extends Model
{

    use AutoCreatedUpdated;




    public function source()
    {
        return $this->morphTo();
    }


    public function transaction()
    {
        return $this->belongsTo(HotelTransection::class, 'hotel_transaction_id');
    }


    public function account()
    {
        return $this->belongsTo(AccountType::class, 'payment_type');
    }


    public function nightClosing()
    {
        return $this->hasMany(NightAuditSummary::class, 'date', 'date');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'source_id');
    }





    public function nightClosingLedger()
    {
        return $this->hasMany(NightAuditTransaction::class, 'transaction_ledger_id');
    }

}
