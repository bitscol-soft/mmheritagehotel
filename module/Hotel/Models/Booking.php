<?php

namespace Module\Hotel\Models;

use App\Traits\AutoCreatedUpdated;
use Module\Account\Models\Transaction;
use Module\HotelService\Models\Service\HotelServiceSale;
use Module\Restaurant\Models\Sale;

class Booking extends Model
{



    use AutoCreatedUpdated;


    protected $table = 'booking';



    protected $casts = [
        'check_in_time'     => 'datetime',
        'check_out_time'    => 'datetime',
    ];

    /**
     * Using for morph relation
     */
    public function details()
    {
        return $this->hasMany(BookingDetails::class, 'booking_id', 'id');
    }

    public function bookingDetails()
    {
        return $this->hasMany(BookingDetails::class, 'booking_id', 'id');
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


    public function booking_guests()
    {
        return $this->hasMany(BookingGuestDetail::class, 'booking_id');
    }

    public function booking_members()
    {
        return $this->hasMany(BookingMemberDetail::class, 'booking_id');
    }



    public function payBy()
    {
        return $this->belongsTo(BookingMemberDetail::class, 'pay_by');
    }




    public function bookingDates()
    {
        return $this->hasMany(BookingDateDetails::class, 'booking_id', 'id');
    }







    public function bookingDetail()
    {
        return $this->hasOne(BookingDetails::class, 'booking_id', 'id');
    }





    public function categoryName()
    {
        return $this->hasOne(BookingDetails::class, 'booking_id', 'id');
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
        return $this->hasMany(BookingDetails::class);
    }




    public function guestInfo()
    {
        return $this->hasOne(Guest::class, 'id', 'customer_id');
    }


    public function guestImage()
    {
        return $this->hasMany(ImageStoreGuest::class, 'booking_id', 'id');
    }



    public function customer()
    {
        return $this->belongsTo(Guest::class,'customer_id');
    }



    public function transection()
    {
        return $this->morphOne(HotelTransection::class, 'source');
    }

    public function hotel_transaction()
    {
        return $this->hasMany(HotelTransection::class, 'booking_id', 'id');
    }


    // TEST RELATION
    public function hotel_transactions()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }
    // END




    public function transection_ledgers()
    {
        return $this->morphMany(HotelTransactionLedger::class, 'source');
    }







    public function transections()
    {
        return $this->morphMany(HotelTransection::class, 'source');
    }




    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }







    public function hotelServiceSale()
    {
        return $this->hasMany(HotelServiceSale::class, 'booking_id');
    }






    public function resturentServiceSale()
    {
        return $this->hasMany(Sale::class, 'hotel_booking_id');
    }





    public function bookingAdjusts()
    {
        return $this->hasMany(BookingAdjust::class, 'booking_id');
    }




    public function nightClosing()
    {
        return $this->hasMany(NightAuditSummary::class, 'date', 'booking_date');
    }



    public function members()
    {
        return $this->hasMany(BookingMemberDetail::class, 'booking_id');
    }


    public function bookingExtraCharge()
    {
        return $this->hasMany(BookingExtraCharge::class, 'booking_id');
    }


    public function ExtraCharge()
    {
        return $this->hasOne(BookingExtraCharge::class, 'booking_id');
    }


    public function scopeCancel($query)
    {
        $query->where('status', 4);
    }


    public function bar_pay_booking()
    {
        return $this->hasMany(Sale::class, 'pay_booking_id');
    }



    public function bookingTransactions()
    {
        return $this->hasMany(HotelTransection::class, 'source_id')->whereIn('source_type', );
    }


    public function bookingTransaction()
    {
        return $this->hasOne(HotelTransection::class, 'source_id')->whereIn('source_type', 'Booking');
    }

   public function guest()
    {
        return $this->belongsTo(Guest::class, 'customer_id');
    }
}
