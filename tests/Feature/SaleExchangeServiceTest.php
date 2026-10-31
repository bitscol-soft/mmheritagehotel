<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Module\Bar\Models\Stock;
use Module\Bar\Models\SaleExchange;
use Module\Bar\Services\SaleExchangeService;

/**
 * Sale Exchange (BUGS #48).
 *
 * The interesting part of an exchange is the arithmetic: what the guest hands
 * back, what they take away, and which side of that pays. These tests pin the
 * three cases the shop floor actually produces, plus the stock direction, which
 * is easy to get backwards (returned goods go IN, replacement goods go OUT).
 */
class SaleExchangeServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('rst_sale_exchanges', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('company_id')->default(1);
            $t->unsignedBigInteger('created_by')->default(1);
            $t->unsignedBigInteger('updated_by')->nullable();
            $t->unsignedBigInteger('customer_id')->nullable();
            $t->string('invoice_no')->nullable();
            $t->date('date');
            $t->double('subtotal')->nullable();
            $t->double('return_amount')->nullable();
            $t->double('exchange_amount')->nullable();
            $t->double('payable_amount')->nullable();
            $t->double('due_amount')->nullable();
            $t->double('change_amount')->nullable();
            $t->unsignedBigInteger('sale_id')->nullable();
            $t->tinyInteger('is_bar')->default(1);
            $t->timestamps();
        });

        Schema::create('rst_sale_exchange_details', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('sale_exchange_id');
            $t->unsignedBigInteger('product_id');
            $t->unsignedBigInteger('sale_id')->nullable();
            $t->string('direction')->default('out');
            $t->double('quantity');
            $t->double('item_price');
            $t->double('total_amount')->nullable();
            $t->double('item_discount')->default(0);
            $t->tinyInteger('is_bar')->default(1);
            $t->timestamps();
        });

        Schema::create('rst_product_stocks', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('product_id');
            $t->double('sold_quantity')->default(0);
            $t->double('return_quantity')->default(0);
            $t->double('purchased_quantity')->default(0);
            $t->double('opening_quantity')->default(0);
            $t->timestamps();
        });

        Schema::create('invoice_generate', function (Blueprint $t) {
            $t->id();
            $t->string('type');
            $t->string('year');
            $t->integer('next_id')->default(1);
            $t->timestamps();
        });
    }

    /** @return array the request shape the create form actually posts */
    private function payload(array $over = [])
    {
        return array_merge([
            'date'            => '2026-10-20',
            'customer_id'     => null,
            'sale_id'         => null,
            'product_ids'     => [7],
            'return_quantity' => [2],
            'product_cost'    => [100],
            'sale_ids'        => [55],
            'item_discount'   => [0],
            'exchange_amount' => 500,
        ], $over);
    }

    private function runExchange(array $over = [])
    {
        request()->merge($this->payload($over));

        $service = new SaleExchangeService();
        $exchange = $service->store();
        $service->storeItem();

        return $exchange->fresh();
    }

    public function test_new_goods_more_expensive_than_the_return_leaves_a_due(): void
    {
        $exchange = $this->runExchange();

        // 2 x 100 returned, 500 of replacement goods -> the guest owes 300
        $this->assertEquals(200, (float) $exchange->return_amount);
        $this->assertEquals(500, (float) $exchange->subtotal);
        $this->assertEquals(300, (float) $exchange->payable_amount);
        $this->assertEquals(300, (float) $exchange->due_amount);
        $this->assertEquals(0, (float) $exchange->change_amount);
    }

    public function test_replacement_cheaper_than_the_return_gives_change_back(): void
    {
        $exchange = $this->runExchange(['exchange_amount' => 150]);

        // 2 x 100 returned, 150 taken -> the guest gets 50 back
        $this->assertEquals(200, (float) $exchange->return_amount);
        $this->assertEquals(150, (float) $exchange->subtotal);
        $this->assertEquals(-50, (float) $exchange->payable_amount);
        $this->assertEquals(0, (float) $exchange->due_amount);
        $this->assertEquals(50, (float) $exchange->change_amount);
    }

    public function test_an_even_exchange_settles_nothing(): void
    {
        $exchange = $this->runExchange(['exchange_amount' => 200]);

        $this->assertEquals(0, (float) $exchange->payable_amount);
        $this->assertEquals(0, (float) $exchange->due_amount);
        $this->assertEquals(0, (float) $exchange->change_amount);
    }

    public function test_returned_goods_go_back_into_stock(): void
    {
        $exchange = $this->runExchange();

        $stock = Stock::where('product_id', 7)->first();

        $this->assertNotNull($stock, 'a stock row should be created for a product with none');
        $this->assertEquals(2, (float) $stock->return_quantity);
        $this->assertEquals(0, (float) $stock->sold_quantity);
        $this->assertEquals(1, $exchange->items->count());
    }

    public function test_replacement_lines_move_stock_out_and_are_priced_per_line(): void
    {
        $exchange = $this->runExchange([
            'product_ids'     => [7, 9],
            'return_quantity' => [2, 1],
            'product_cost'    => [100, 300],
            'sale_ids'        => [55, 55],
            'item_discount'   => [0, 0],
            'directions'      => ['out', 'in'],
            'exchange_amount' => 300,
        ]);

        // one side returns 2x100 and the other takes 1x300: an even swap
        $this->assertEquals(200, (float) $exchange->return_amount);
        $this->assertEquals(300, (float) $exchange->subtotal);
        $this->assertEquals(100, (float) $exchange->payable_amount);

        $returned = Stock::where('product_id', 7)->first();
        $taken    = Stock::where('product_id', 9)->first();

        $this->assertEquals(2, (float) $returned->return_quantity);
        $this->assertEquals(0, (float) $taken->return_quantity);
        $this->assertEquals(1, (float) $taken->sold_quantity);
        $this->assertSame('in', $exchange->items->firstWhere('product_id', 9)->direction);
    }

    public function test_a_zero_valued_line_is_ignored_rather_than_saved(): void
    {
        $exchange = $this->runExchange([
            'product_ids'     => [7, 8],
            'return_quantity' => [2, 0],
            'product_cost'    => [100, 999],
            'sale_ids'        => [55, 55],
            'item_discount'   => [0, 0],
        ]);

        $this->assertEquals(1, $exchange->items->count());
        $this->assertNull(SaleExchange::query()->where('id', $exchange->id)->first()->items
            ->firstWhere('product_id', 8));
    }

    public function test_every_exchange_gets_an_invoice_number_and_bar_flag(): void
    {
        $exchange = $this->runExchange();

        $this->assertNotEmpty($exchange->invoice_no);
        $this->assertStringStartsWith(date('Y-m'), $exchange->invoice_no);
        $this->assertEquals(1, (int) $exchange->is_bar);
    }
}
