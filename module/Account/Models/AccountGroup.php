<?php


namespace Module\Account\Models;


use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountGroup extends Model
{
    public function accountControls()
    {
        return $this->hasMany(AccountControl::class);
    }


    public function accountControl()
    {
        return $this->hasOne(AccountControl::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'account_group_id', 'id');
    }
}
