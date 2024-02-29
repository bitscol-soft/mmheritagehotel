<?php

namespace Module\Hotel\Models;


class NightAuditTransaction extends Model
{


    public function transactions()
    {
        return $this->hasMany(HotelTransection::class, 'id', 'transaction_id');
    }

    public function transaction_ledgers()
    {
        return $this->hasMany(HotelTransactionLedger::class, 'id', 'transaction_ledger_id');
    }

    public function transaction_ledger()
    {
        return $this->belongsTo(HotelTransactionLedger::class, 'transaction_ledger_id');
    }

}
