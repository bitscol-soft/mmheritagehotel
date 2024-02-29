<?php


namespace Module\Account\Models;

use App\Models\Company;
use App\Traits\AutoCreatedUpdated;

class Voucher extends Model
{
    use AutoCreatedUpdated;


    public function company()
    {
        return $this->belongsTo(Company::class);
    }



    public function details()
    {
        return $this->hasMany(VoucherDetail::class, 'voucher_id');
    }




    public function scopePayment($query)
    {
        $query->where('voucher_type', 'Payment');
    }




    public function scopeReceive($query)
    {
        $query->where('voucher_type', 'Receive');
    }




    public function scopeContra($query)
    {
        $query->where('voucher_type', 'Contra');
    }




    public function scopeJournal($query)
    {
        $query->where('voucher_type', 'Journal');
    }
}
