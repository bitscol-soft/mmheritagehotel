<?php
// Shared sample data/renderer for the dashboard room board. No database or application boot.
function mm_board_sample_categories()
{
    $today = date('Y-m-d');
    $room = function (array $overrides) {
        return (object) array_merge(['id' => 0, 'room_number' => '', 'status' => 1, 'is_booked' => 0, 'is_reservation' => 0, 'is_checkin' => 0,
            'booking_cart_count' => 0, 'today_checkout' => 0, 'rent' => 4500, 'beds' => '1', 'max_guests' => 2, 'smoking_status' => 0,
            'booking_dates' => collect([])], $overrides);
    };
    $stay = function ($status, $checkout) use ($today) {
        $booking = (object) ['id' => 900 + $status, 'status' => $status, 'check_out_date' => $checkout, 'check_in_time' => '14:00', 'check_out_time' => '',
            'check_in_note' => "Late arrival <b>note</b>", 'guestInfo' => (object) ['name' => "Aisha O'Neil <script>alert(1)</script>", 'phone_no' => '01700000000', 'nid_no' => 'P1234567']];
        return collect([(object) ['status' => $status, 'date' => $today, 'booking_id' => $booking->id, 'booking' => $booking]]);
    };
    $category = function ($id, $name, $bed, array $rooms, $price) {
        return (object) ['id' => $id, 'name' => $name, 'bed_details' => $bed, 'price' => $price, 'guest_capacity' => 2, 'can_sleep' => 2, 'room_sqft' => 320,
            'description' => 'Quiet room with city view.', 'rooms' => collect($rooms)];
    };
    return collect([
        $category(1, 'Deluxe King', 'King Bed', [
            $room(['id' => 1, 'room_number' => '101']), $room(['id' => 2, 'room_number' => '102']),
            $room(['id' => 3, 'room_number' => '103', 'is_booked' => 1, 'booking_dates' => $stay(0, date('Y-m-d', strtotime('+2 day')))]),
            $room(['id' => 4, 'room_number' => '104', 'is_reservation' => 1, 'booking_dates' => $stay(2, date('Y-m-d', strtotime('+2 day')))]),
            $room(['id' => 5, 'room_number' => '105', 'status' => 0]), $room(['id' => 6, 'room_number' => '106', 'status' => 2]),
            $room(['id' => 7, 'room_number' => '107', 'is_checkin' => 1, 'booking_dates' => $stay(1, $today)]),
        ], '4,500'),
        $category(2, 'Superior Twin', 'Twin Beds', array_map(function ($n) use ($room) { return $room(['id' => 20 + $n, 'room_number' => (string) (200 + $n), 'beds' => '2']); }, range(1, 8)), '3,200'),
        $category(3, 'Standard Single', 'Single bed', array_map(function ($n) use ($room) { return $room(['id' => 30 + $n, 'room_number' => (string) (300 + $n), 'beds' => '1', 'rent' => 2500]); }, range(1, 4)), '2,500'),
        $category(4, 'Family Suite', 'Triple', [$room(['id' => 41, 'room_number' => '401', 'beds' => '3']), $room(['id' => 42, 'room_number' => '402', 'beds' => '4'])], '6,000'),
        $category(5, 'Double Room', 'Double Bed', [$room(['id' => 51, 'room_number' => '501', 'beds' => '1'])], '3,800'),
        $category(6, 'Large Suite', '', [$room(['id' => 61, 'room_number' => '601', 'beds' => '5'])], '9,000'),
    ]);
}

function mm_board_render($app, $categories)
{
    return $app->make('view')->make('home._inc.room-board', ['categories' => $categories, 'mix_date' => date('m/d/Y') . ' - ' . date('m/d/Y', strtotime('+1 day'))])->render();
}
