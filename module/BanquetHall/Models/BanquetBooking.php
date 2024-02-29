<?php

namespace Module\BanquetHall\Models;

use App\Model;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Guest;
use App\Traits\AutoCreatedUpdated;
use Module\Hotel\Models\AccountType;
use Module\Account\Models\Transaction;
use Module\Hotel\Models\BookingPurpose;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Models\HotelTransactionLedger;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BanquetBooking extends Model
{
    use HasFactory, AutoCreatedUpdated;


    public function details()
    {
        return $this->hasMany(BanquetBookingDetails::class, 'booking_id', 'id');
    }



    public function bookingDetails()
    {
        return $this->hasMany(BanquetBookingDetails::class, 'booking_id', 'id');
    }

    public function ItemDetails()
    {
        return $this->hasMany(BanquetItemDetails::class, 'booking_id', 'id');
    }
    public function ProductDetails()
    {
        return $this->hasMany(BanquetProductDetails::class, 'booking_id', 'id');
    }

    public function booking_purpose()
    {
        return $this->belongsTo(BookingPurpose::class, 'purpose_id', 'id');
    }


    public function booking_platform()
    {
        return $this->belongsTo(BookingPurpose::class, 'platform_id', 'id');
    }

    public function booking_type()
    {
        return $this->belongsTo(BookingPurpose::class, 'book_type', 'id');
    }



    // public function payBy()
    // {
    //     return $this->belongsTo(BookingMemberDetail::class, 'pay_by');
    // }




    // public function bookingDates()
    // {
    //     return $this->hasMany(BookingDateDetails::class, 'booking_id', 'id');
    // }



    public function bookingDetail()
    {
        return $this->hasOne(BanquetBookingDetails::class, 'booking_id', 'id');
    }




    public function getVat()
    {
        return $this->belongsTo(Vat::class, 'vat_id', 'id');
    }





    public function paymentType()
    {
        return $this->hasOne(AccountType::class, 'id', 'payment_id');
    }






    public function bookingList()
    {
        return $this->hasMany(BanquetBookingDetails::class);
    }






    public function guestInfo()
    {
        return $this->hasOne(Guest::class, 'id', 'customer_id');
    }



    public function customer()
    {
        return $this->belongsTo(Guest::class,'customer_id');
    }



    public function transection()
    {
        return $this->morphOne(HotelTransection::class, 'source');
    }

    public function hall_transaction()
    {
        return $this->hasMany(HotelTransection::class, 'source_id', 'id');
    }


    // TEST RELATION
    // public function hotel_transactions()
    // {
    //     return $this->morphMany(HotelTransection::class, 'source');
    // }
    // END




    public function transection_ledgers()
    {
        return $this->morphMany(HotelTransactionLedger::class, 'source');
    }







    // public function transections()
    // {
    //     return $this->morphMany(HotelTransection::class, 'source');
    // }




    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }






    // public function nightClosing()
    // {
    //     return $this->hasMany(NightAuditSummary::class, 'date', 'booking_date');
    // }



    // public function members()
    // {
    //     return $this->hasMany(BookingMemberDetail::class, 'booking_id');
    // }


    // public function bookingExtraCharge()
    // {
    //     return $this->hasMany(BookingExtraCharge::class, 'booking_id');
    // }


    // public function ExtraCharge()
    // {
    //     return $this->hasOne(BookingExtraCharge::class, 'booking_id');
    // }


    // public function scopeCancel($query)
    // {
    //     $query->where('status', 4);
    // }


    // public function bar_pay_booking()
    // {
    //     return $this->hasMany(Sale::class, 'pay_booking_id');
    // }



    // public function bookingTransactions()
    // {
    //     return $this->hasMany(HotelTransection::class, 'source_id')->whereIn('source_type', );
    // }


    // public function bookingTransaction()
    // {
    //     return $this->hasOne(HotelTransection::class, 'source_id')->whereIn('source_type', 'Booking');
    // }

   public function guest()
    {
        return $this->belongsTo(Guest::class, 'customer_id');
    }
}
