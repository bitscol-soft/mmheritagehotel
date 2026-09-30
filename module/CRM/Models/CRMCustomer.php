<?php

namespace Module\CRM\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CRM Customer / Company master.
 *
 * Restores the model that core screens (Hotel bookings, guests, collections,
 * banquet, restaurant POS, invoices via getCrmCompany()) import. The module
 * previously shipped only as an unresolvable submodule gitlink; the data
 * lives in the `c_r_m_customers` table (created by the original CRM migrations).
 */
class CRMCustomer extends Model
{
    protected $table = 'c_r_m_customers';

    protected $guarded = [];
}
