<?php

namespace Module\Hotel\Models;

use App\Model;
use App\Traits\AutoCreatedUpdated;

class NightAuditSummary extends Model
{

    use AutoCreatedUpdated;


    public function details()
    {
        return $this->hasMany(NightAuditDetail::class, 'audit_id', 'id');
    }


    public function auditTransactions()
    {
        return $this->hasMany(NightAuditTransaction::class, 'audit_id', 'id');
    }


    public function room_details()
    {
        return $this->hasMany(NightAuditRoomDetail::class, 'audit_id', 'id');
    }
}
