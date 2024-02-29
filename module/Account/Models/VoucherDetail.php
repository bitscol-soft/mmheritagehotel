<?php


namespace Module\Account\Models;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class VoucherDetail extends Model
{


    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }



    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }



    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'transactionable_id', 'id')->where('transactionable_type', 'Voucher Detail');
    }



    public function account()
    {
        return $this->belongsTo(Account::class);
    }



    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id', 'supplier_id');
    }



    public function customer()
    {
        return $this->hasOne(Supplier::class,'id', 'customer_id');
    }
}
