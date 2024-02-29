<?php

namespace Module\Hotel\Models;


class NightAuditDetail extends Model
{


    public function transaction()
    {
        return $this->belongsTo(HotelTransection::class, 'transaction_id');
    }

    public function transactions()
    {
        return $this->hasMany(HotelTransection::class, 'id', 'transaction_id');
    }
    public function NightAudit()
    {
        return $this->hasMany(NightAuditSummary::class, 'id', 'audit_id');
    }
}
