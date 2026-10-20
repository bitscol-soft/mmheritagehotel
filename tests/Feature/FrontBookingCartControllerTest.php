<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Concerns\CreatesAccSchema;
use Module\Hotel\Models\RoomPhotos;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\NightAuditSummary;
use App\Http\Controllers\Front\BookingController;

class FrontBookingCartControllerTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();

        RoomCategory::create(['id' => 3, 'name' => 'Deluxe', 'price' => 100.00]);
        RoomPhotos::create(['id' => 1, 'name' => 'd.jpg', 'category_id' => 3, 'relative_path' => 'uploads/']);
    }

    protected function addRequest(array $over = []): Request
    {
        return new Request(array_merge([
            'category_id'     => 3,
            'nights'          => 2,
            'date'            => '',
            'check_in_date'   => '2026-10-20',
            'check_out_date'  => '2026-10-22',
            'guest_capacity'  => 2,
        ], $over));
    }

    public function test_add_to_cart_stores_the_item_in_the_session_and_keeps_the_json_contract(): void
    {
        $controller = app(BookingController::class);

        $res = $controller->addToCart($this->addRequest())->getData(true);

        $this->assertSame(1, $res['return']);
        $this->assertSame('Add to cart Successfully', $res['status']);
        $this->assertSame(1, $res['cart_count']);
        $this->assertSame(3, $res['booking_item']['item_id']);
        $this->assertSame('Deluxe', $res['booking_item']['item_name']);
        $this->assertSame(200.0, (float) $res['booking_item']['item_price']);   // price x nights
        $this->assertSame('d.jpg', $res['booking_item']['item_img']);
        $this->assertSame('uploads/', $res['booking_item']['item_img_path']);
        $this->assertSame('2026-10-20', $res['booking_item']['check_in']);
        $this->assertSame('2026-10-22', $res['booking_item']['check_out']);
        $this->assertSame(2, (int) $res['booking_item']['nights']);

        // server-side session store, not a cookie
        $items = front_cart_items();
        $this->assertCount(1, $items);
        $this->assertSame(3, $items[0]['item_id']);
        $this->assertNull(request()->cookie('booking_cart'));
    }

    public function test_adding_the_same_category_twice_is_refused(): void
    {
        $controller = app(BookingController::class);
        $controller->addToCart($this->addRequest());

        $res = $controller->addToCart($this->addRequest())->getData(true);

        $this->assertSame(0, $res['return']);
        $this->assertStringContainsString('Already Added to Cart', $res['status']);
        $this->assertCount(1, front_cart_items());
    }

    public function test_remove_to_cart_removes_the_item_and_reports_the_count(): void
    {
        $controller = app(BookingController::class);
        $controller->addToCart($this->addRequest());

        $res = $controller->removeToCart(new Request(['booking_id' => 3]))->getData(true);

        $this->assertSame('Item Removed from Cart', $res['status']);
        $this->assertSame(0, $res['cart_count']);
        $this->assertSame([], front_cart_items());
    }

    public function test_remove_on_an_empty_cart_is_safe(): void
    {
        $controller = app(BookingController::class);
        $res = $controller->removeToCart(new Request(['booking_id' => 3]));

        $this->assertNull($res);
        $this->assertSame([], front_cart_items());
    }

    public function test_night_audit_blocks_adding_to_cart(): void
    {
        NightAuditSummary::create(['date' => '2026-10-20']);

        $controller = app(BookingController::class);
        $res = $controller->addToCart($this->addRequest())->getData(true);

        $this->assertSame(0, $res['return']);
        $this->assertSame('Unable to booked.', $res['status']);
        $this->assertSame([], front_cart_items());
    }
}
