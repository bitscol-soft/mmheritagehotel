<?php

namespace Module\Restaurant\Models;

use App\Model;

/**
 * One line of a sale exchange.
 *
 * `direction` is what makes an exchange different from a return:
 *   out - the guest is handing back an item from the original invoice
 *         (stock increases, money owed to the guest)
 *   in  - the guest is taking a replacement item
 *         (stock decreases, money owed by the guest)
 *
 * A line keeps `sale_id` so the original invoice it came from stays traceable.
 */
class SaleExchangeDetail extends Model
{
    protected $table = 'rst_sale_exchange_details';

    protected $fillable = [
        'sale_exchange_id',
        'product_id',
        'sale_id',
        'direction',
        'quantity',
        'item_price',
        'total_amount',
        'item_discount',
        'is_bar',
    ];

    public function sale_exchange()
    {
        return $this->belongsTo(SaleExchange::class, 'sale_exchange_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
