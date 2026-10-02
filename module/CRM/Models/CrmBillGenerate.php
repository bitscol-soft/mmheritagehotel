<?php

namespace Module\CRM\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CRM project bill/invoice rows (used by the crm:send-mail scheduled command).
 */
class CrmBillGenerate extends Model
{
    protected $table = 'c_r_m_bill_generates';

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(CRMCustomer::class, 'company_id');
    }
}
