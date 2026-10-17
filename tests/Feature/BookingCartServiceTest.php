<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Tests\Concerns\CreatesAccSchema;
use App\Models\User;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\RoomPhotos;
use Module\Hotel\Models\BookingCart;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\NightAuditSummary;
use Module\Hotel\Services\BookingCartService;

class BookingCartServiceTest extends TestCase
{
    use CreatesAccSchema;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();

        RoomCategory::create(['id' => 3, 'name' => 'Deluxe', 'price' => 100.00]);
        Rooms::create(['id' => 11, 'name' => 'D1', 'room_number' => 'D1', 'room_category' => 3]);
        Rooms::create(['id' => 12, 'name' => 'D2', 'room_number' => 'D2', 'room_category' => 3]);

        // the legacy UI sends "check_in - check_out" (slash dates)
        request()->merge(['date' => '10/20/2026 - 10/22/2026']);
    }

    protected function addRequest(int $roomId, array $over = []): Request
    {
        return new Request(array_merge([
            'date'        => '10/20/2026 - 10/22/2026',
            'room_id'     => $roomId,
            'category_id' => 3,
            'guest'       => 2,
            'infant'      => 0,
        ], $over));
    }

    public function test_add_creates_a_user_scoped_cart_row_and_keeps_the_json_contract(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        $svc = new BookingCartService();
        $res = $svc->add($this->addRequest(11))->getData(true);

        $this->assertSame(1, $res['status']);
        $this->assertSame('Successfully added.', $res['data']);
        $this->assertSame('Success', $res['message']);
        $this->assertSame(1, $res['booking_count']);

        $row = BookingCart::query()->first();
        $this->assertNotNull($row);
        $this->assertSame(11, (int) $row->room_id);
        $this->assertSame(3, (int) $row->room_category_id);
        $this->assertSame(2, (int) $row->nights);
        $this->assertSame('2026-10-20', $row->check_in_date);
        $this->assertSame('2026-10-22', $row->check_out_date);
        $this->assertSame(100.0, (float) $row->category_price);
        $this->assertSame(1, (int) $row->user_id);
        $this->assertSame(1, (int) $row->status);
    }

    public function test_duplicate_room_is_refused_without_a_second_row(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        $svc = new BookingCartService();
        $svc->add($this->addRequest(11));
        $res = $svc->add($this->addRequest(11))->getData(true);

        $this->assertSame(1, $res['status']);
        $this->assertSame('Your request have been stored', $res['data']);
        $this->assertSame(1, BookingCart::query()->count());
    }

    public function test_carts_are_scoped_per_user(): void
    {
        $userA = User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']);
        $userB = User::forceCreate(['name' => 'B', 'email' => 'b@x.test', 'password' => 'x']);

        $this->actingAs($userA);
        $svc = new BookingCartService();
        $svc->add($this->addRequest(11));
        $svc->add($this->addRequest(12));
        $this->assertSame(2, $svc->count());

        $this->actingAs($userB);
        $svcB = new BookingCartService();
        $this->assertSame(0, $svcB->count());
        $res = $svcB->add($this->addRequest(11))->getData(true);   // same room is fine for another user
        $this->assertSame(1, $res['booking_count']);

        $this->actingAs($userA);
        $this->assertSame(2, (new BookingCartService())->count());
    }

    public function test_remove_and_clear_keep_the_contract(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        $svc = new BookingCartService();
        $svc->add($this->addRequest(11));
        $svc->add($this->addRequest(12));

        $res = $svc->remove(11)->getData(true);
        $this->assertSame(1, $res['status']);
        $this->assertSame('Successfully Removed!', $res['data']);
        $this->assertSame('Success', $res['message']);
        $this->assertSame(1, $res['cart_count']);
        $this->assertSame(1, BookingCart::query()->count());

        $svc->clear();
        $this->assertSame(0, BookingCart::query()->count());
    }

    public function test_all_maps_rows_to_the_legacy_item_shape(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        $svc = new BookingCartService();
        $svc->add($this->addRequest(11));

        $items = $svc->all();
        $this->assertCount(1, $items);
        $item = $items[0];

        $this->assertSame(3, $item['category_id']);
        $this->assertSame(11, $item['room_id']);
        $this->assertSame('D1', $item['room_number']);
        $this->assertSame('Deluxe', $item['category_name']);
        $this->assertSame('2026-10-20', $item['check_in']);
        $this->assertSame('2026-10-22', $item['check_out']);
        $this->assertSame(2, (int) $item['nights']);
        // the frozen booking_next view reads $data['category_price']['price']
        $this->assertArrayHasKey('price', $item['category_price']);
        $this->assertEquals(100.0, $item['category_price']['price']);
    }

    public function test_legacy_store_rejects_rooms_on_a_closed_night(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        NightAuditSummary::create(['date' => '2026-10-20']);

        $svc = new BookingCartService();
        $res = $svc->store($this->addRequest(11))->getData(true);

        $this->assertSame(0, $res['return']);
        $this->assertSame('Unable to booked.', $res['status']);
        $this->assertSame(0, BookingCart::query()->count());
    }

    public function test_legacy_store_creates_the_row_when_the_night_is_open(): void
    {
        $this->actingAs(User::forceCreate(['name' => 'A', 'email' => 'a@x.test', 'password' => 'x']));

        $svc = new BookingCartService();
        $res = $svc->store($this->addRequest(11))->getData(true);

        $this->assertSame('Your request have been stored.', $res['status']);
        $this->assertSame('Success', $res['message']);
        $this->assertSame(1, BookingCart::query()->count());
        $this->assertSame(2, (int) BookingCart::query()->first()->nights);
    }
}
