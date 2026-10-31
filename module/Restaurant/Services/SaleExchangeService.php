<?php

namespace Module\Restaurant\Services;

use Module\Restaurant\Models\Stock;
use Module\Restaurant\Models\SaleExchange;
use Module\HotelService\Services\HotelTransactionService;

/**
 * Sale Exchange (Restaurant).
 *
 * Reuses the return service's building blocks instead of re-deriving money or
 * stock arithmetic: the invoice number comes from BarTransectionService and the
 * settlement is posted by HotelTransactionService, exactly as a sale return does.
 *
 * `is_bar` is 1 here; the Restaurant service flips it to 0. Both write the same
 * shared `rst_sale_exchanges` table, the same way rst_sales/rst_sale_returns work.
 */
class SaleExchangeService
{
    /** @var \Illuminate\Http\Request */
    private $request;

    /** @var SaleExchange */
    public $exchange;

    public $invoice_no;

    public $transection_service;

    public $is_bar = 0;

    public function __construct()
    {
        $this->request = \request();
        $this->transection_service = new BarTransectionService();
        $this->invoice_no = $this->transection_service->getInvoiceNo('Restaurant Sale Exchange');
    }

    /**
     * Header. The money fields are recomputed from the two sides rather than
     * trusted from the form, so a tampered POST cannot settle a different amount
     * than the goods it describes.
     */
    public function store()
    {
        $out = $this->sideTotal('out');   // handed back to us
        $in  = $this->incomingTotal();    // taken by the guest

        $payable = round($in - $out, 2);

        $this->exchange = SaleExchange::create([
            'customer_id'     => $this->request->customer_id ?: null,
            'date'            => $this->request->date ?: date('Y-m-d'),
            'sale_id'         => $this->request->sale_id ?: null,
            'subtotal'        => $in,
            'return_amount'   => $out,
            'exchange_amount' => $out,
            'payable_amount'  => $payable,
            'due_amount'      => max($payable, 0),
            'change_amount'   => $payable < 0 ? abs($payable) : 0,
            'is_bar'          => $this->is_bar,
        ]);

        $this->exchange->update(['invoice_no' => $this->invoice_no]);
        $this->transection_service->setNextInvoiceNo('Restaurant Sale Exchange', date('Y-m'));

        return $this->exchange;
    }

    /**
     * Detail rows plus the stock moves that go with them.
     *
     * out -> stock returns (return_quantity grows)
     * in  -> stock leaves  (sold_quantity grows)
     */
    public function storeItem()
    {
        foreach ((array) $this->request->product_ids as $key => $product_id) {
            $direction = $this->request->directions[$key] ?? 'out';
            $quantity  = (float) ($this->request->return_quantity[$key] ?? 0);

            if (!$product_id || $quantity <= 0) {
                continue;
            }

            $price = (float) ($this->request->product_cost[$key] ?? 0);

            $this->exchange->items()->create([
                'sale_id'       => $this->request->sale_ids[$key] ?? null,
                'product_id'    => $product_id,
                'direction'     => $direction === 'in' ? 'in' : 'out',
                'quantity'      => $quantity,
                'item_price'    => $price,
                'total_amount'  => round($price * $quantity, 2),
                'item_discount' => (float) ($this->request->item_discount[$key] ?? 0),
                'is_bar'        => $this->is_bar,
            ]);

            if ($direction === 'in') {
                $this->stockOut($product_id, $quantity);
            } else {
                $this->stockIn($product_id, $quantity);
            }
        }
    }

    /** Settlement for the difference. Mirrors SaleReturnService::makePayment(). */
    public function settle($account_type_id = null)
    {
        if (!$this->exchange || (float) $this->exchange->payable_amount === 0.0) {
            return;
        }

        (new HotelTransactionService())->storeTransaction(
            $this->exchange,
            $this->exchange->id,
            'Restaurant Sale Exchange',
            $account_type_id,
            $this->exchange->payable_amount,
            $this->request->discount ?? 0,
            $this->request->paid_amount ?? 0,
            $this->request->vat ?? 0,
            $this->request->service_charge ?? 0,
            $this->request->booking_id ?? null,
            $this->invoice_no,
            $this->exchange->date ?? today_from_system()
        );
    }

    /**
     * Value of the replacement goods.
     *
     * Two shapes are supported because the shipped form and a full line-item
     * exchange are both valid:
     *   - line items with direction "in" (a future two-sided form), else
     *   - the `exchange_amount` field the current form asks the cashier to type,
     *     which is what the original screen was built around.
     */
    private function incomingTotal()
    {
        $fromLines = $this->sideTotal('in');

        if ($fromLines > 0) {
            return $fromLines;
        }

        return round((float) ($this->request->exchange_amount ?? 0), 2);
    }

    private function sideTotal($direction)
    {
        $quantities = (array) $this->request->return_quantity;
        $prices     = (array) $this->request->product_cost;
        $directions = (array) ($this->request->directions ?? []);
        $total = 0.0;

        foreach ($quantities as $key => $quantity) {
            $lineDirection = $directions[$key] ?? 'out';
            if (($lineDirection === 'in' ? 'in' : 'out') !== $direction) {
                continue;
            }
            $total += (float) $quantity * (float) ($prices[$key] ?? 0);
        }

        return round($total, 2);
    }

    /** Goods handed back: they go back into stock. */
    public function stockIn($product_id, $qty)
    {
        $product = Stock::where('product_id', $product_id)->first();

        if ($product) {
            $product->increment('return_quantity', $qty);
        } else {
            Stock::create([
                'product_id'         => $product_id,
                'sold_quantity'      => 0,
                'return_quantity'    => $qty,
                'purchased_quantity' => 0,
                'opening_quantity'   => 0,
            ]);
        }
    }

    /** Replacement goods: they leave stock. */
    public function stockOut($product_id, $qty)
    {
        $product = Stock::where('product_id', $product_id)->first();

        if ($product) {
            $product->increment('sold_quantity', $qty);
        } else {
            Stock::create([
                'product_id'         => $product_id,
                'sold_quantity'      => $qty,
                'return_quantity'    => 0,
                'purchased_quantity' => 0,
                'opening_quantity'   => 0,
            ]);
        }
    }
}
