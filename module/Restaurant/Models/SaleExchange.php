<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Models\User;
use App\Models\Company;
use Module\Hotel\Models\Guest;
use Module\Account\Models\Transaction;

/**
 * Sale Exchange (Restaurant).
 *
 * An exchange returns goods from an existing invoice and hands out replacement
 * goods in one document, settling only the difference. The header is shared with
 * the Restaurant module (both write `rst_sale_exchanges`, distinguished by
 * `is_bar`), matching how `rst_sales` and `rst_sale_returns` already work.
 *
 * Money fields mirror `rst_sale_returns` so the existing invoice show/print
 * partials and the transaction service keep working unchanged:
 *   subtotal         value of the goods handed out
 *   return_amount    value of the goods taken back
 *   payable_amount   subtotal - return_amount (the difference the guest owes)
 *   due_amount       unpaid part of payable_amount
 *   change_amount    overpayment handed back in cash
 */
class SaleExchange extends Model
{
    protected $table = 'rst_sale_exchanges';

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'date',
        'subtotal',
        'return_amount',
        'payable_amount',
        'due_amount',
        'change_amount',
        'exchange_amount',
        'sale_id',
        'is_bar',
        'company_id',
        'created_by',
        'updated_by',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = auth()->user();

            // company_id/created_by are NOT NULL. Fall back to the app's helpers so
            // a console command or a test can create an exchange without a session.
            if ($user) {
                $model->fill([
                    'company_id' => $model->company_id ?: $user->company_id,
                    'created_by' => $model->created_by ?: $user->id,
                ]);
            } else {
                // No session (console, test): company_id/created_by are only
                // foreign keys for reporting, so 1 keeps the row valid.
                $model->fill([
                    'company_id' => $model->company_id ?: 1,
                    'created_by' => $model->created_by ?: 1,
                ]);
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->fill(['updated_by' => auth()->id()]);
            }
        });

        static::deleting(function ($model) {
            $model->items()->delete();
            $model->transactions()->delete();
        });
    }

    protected $casts = [
        'date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(SaleExchangeDetail::class, 'sale_exchange_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'customer_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
