<?php


namespace Module\Account\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Module\PosErp\Models\Customer;
use Module\Restaurant\Models\Sale;
use Module\Bar\Models\Sale as BarSale;
use Module\Hotel\Models\Booking;
use Module\HotelService\Models\Service\HotelServiceSale;

Relation::morphMap([

    'Account Opening'           => Account::class,
    'Customer Opening'          => Customer::class,
    'Pos Customer Opening'      => Customer::class,
    'Supplier Opening'          => Supplier::class,


    'Voucher Detail'            => VoucherDetail::class,
    'Fund Transfer'             => FundTransfer::class,

    'Purchase'                  => Purchase::class,
    'Payment'                   => Payment::class,
    'Sale'                      => Sale::class,
    'Account Sale Return'       => SaleReturn::class,
    'Account Purchase Return'   => PurchaseReturn::class,
    'Product Damage'            => Damage::class,
    'Collection'                => Collection::class,
    'Restaurant Sale'           => Sale::class,
    'Bar Sale'                  => BarSale::class,
    'Hotel Service Sale'        => HotelServiceSale::class,
    'Booking'                   => Booking::class,
    'Hall Booking'              => Booking::class,
]);

class Transaction extends Model
{
    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function ($model) {
                $model->fill([
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            });

            static::updating(function ($model) {
                $model->fill([
                    'updated_by' => auth()->id()
                ]);
            });

        }
    }

    public function scopeUserLog($query)
    {
        return $query->with('created_user', 'updated_user');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }

    public function getDescription()
    {
        return $this->transactionable_type == 'Voucher Detail'
            ? optional($this->transactionable)->particular
            : ($this->description ?? optional($this->transactionable)->description);
    }
}
