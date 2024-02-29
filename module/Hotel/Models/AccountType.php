<?php

namespace Module\Hotel\Models;

use Module\Account\Models\Account;

class AccountType extends Model
{
    protected $table = 'hotel_account_type';



    public function hotelTransactions()
    {
        return $this->hasMany(HotelTransection::class, 'account_type_id');
    }


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


}
