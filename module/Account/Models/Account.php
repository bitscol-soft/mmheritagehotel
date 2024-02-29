<?php


namespace Module\Account\Models;

use App\Traits\AutoCreatedUpdatedWithCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Module\Hotel\Models\AccountType;

class Account extends Model
{
    use AutoCreatedUpdatedWithCompany;

    public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class, 'account_group_id');
    }

    public function accountControl(): BelongsTo
    {
        return $this->belongsTo(AccountControl::class, 'account_control_id');
    }

    public function accountSubsidiary(): BelongsTo
    {
        return $this->belongsTo(AccountSubsidiary::class, 'account_subsidiary_id');
    }

    public function opening_balances()
    {
        return $this->hasMany(AccountOpeningBalance::class, 'account_id', 'id');
    }

    public function transaction_items()
    {
        return $this->hasMany(Transaction::class, 'account_id', 'id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function accountType()
    {
        return $this->hasOne(AccountType::class, 'account_id');
    }



    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function scopeCurrentBalance($query)
    {
        $query->where('account_group_id', 1);
    }


    public function scopeAsset($query)
    {
        $query->where('account_group_id', 1);
    }


    public function scopeLiabilities($query)
    {
        $query->where('account_group_id', 2);
    }


    public function scopeCurrentAsset($query)
    {
        $query->where('account_control_id', 1);
    }


    public function scopeFixedAsset($query)
    {
        $query->where('account_control_id', 2);
    }
}
