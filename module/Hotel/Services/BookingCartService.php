<?php

namespace Module\Hotel\Services;

use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\BookingCart;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\NightAuditSummary;

class BookingCartService
{

    public $check_in_date;
    public $check_out_date;


    public function __construct()
    {
        $request = \request();

        $this->check_in_date = null;
        $this->check_out_date = null;

        if ($request && $request->filled('date')) {
            // the room-board / booking-next UI sends "check_in - check_out" (slash dates)
            $split_date = explode('-', $request->date);
            if (count($split_date) >= 2) {
                $this->check_in_date  = date('Y-m-d', strtotime(trim($split_date[0])));
                $this->check_out_date = date('Y-m-d', strtotime(trim($split_date[1])));
            }
        }
    }


    /*
     |--------------------------------------------------------------------------
     | SERVER-SIDE RESERVATION CART (replaces the booking_info cookie)
     |--------------------------------------------------------------------------
     */

    public function count()
    {
        return BookingCart::query()
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->count();
    }


    public function all()
    {
        $items = [];

        $rows = BookingCart::with('room:id,room_number', 'roomCategory')
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            $items[] = [
                'category_id'    => $row->room_category_id,
                'room_id'        => $row->room_id,
                'room_number'    => optional($row->room)->room_number,
                'category_name'  => optional($row->roomCategory)->name,
                'category_price' => optional($row->roomCategory)->toArray() ?: ['price' => $row->category_price],
                'check_in'       => $row->check_in_date,
                'check_out'      => $row->check_out_date,
                'nights'         => $row->nights,
            ];
        }

        return $items;
    }


    public function add($request)
    {
        $split_date = explode('-', $request->date);
        $check_in   = date('Y-m-d', strtotime(trim($split_date[0])));
        $check_out  = date('Y-m-d', strtotime(trim($split_date[1])));

        $exists = BookingCart::query()
            ->where('user_id', auth()->id())
            ->where('room_id', $request->room_id)
            ->where('status', 1)
            ->first();

        if ($exists) {

            return response()->json([
                'status'    => 1,
                'data'      => 'Your request have been stored',
                'message'   => 'Success',
            ]);
        }

        $room     = Rooms::where('id', $request->room_id)->first();
        $category = RoomCategory::where('id', $request->category_id)->first();

        if ($room) {

            BookingCart::create([
                'user_id'          => auth()->id(),
                'room_id'          => $room->id,
                'room_category_id' => $category->id,
                'nights'           => max(1, (int) round((strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24))),
                'guest'            => 1,
                'infant'           => 0,
                'category_price'   => $category->price,
                'check_in_date'    => $check_in,
                'check_out_date'   => $check_out,
                'status'           => 1,
            ]);

            return response()->json([
                'status'        => 1,
                'data'          => 'Successfully added.',
                'message'       => 'Success',
                'booking_count' => $this->count(),
            ]);
        }
    }


    public function remove($room_id)
    {
        BookingCart::query()
            ->where('user_id', auth()->id())
            ->where('room_id', $room_id)
            ->where('status', 1)
            ->delete();

        return response()->json([
            'status'        => 1,
            'data'          => 'Successfully Removed!',
            'message'       => 'Success',
            'cart_count'    => $this->count(),
        ]);
    }


    public function clear()
    {
        BookingCart::query()
            ->where('user_id', auth()->id())
            ->where('status', 1)
            ->delete();
    }


    /*
     |--------------------------------------------------------------------------
     | BOOKING CART (legacy AJAX endpoints: booking-cart / booking-check-by-date)
     |--------------------------------------------------------------------------
     */

    public function store($request)
    {
        $closed = $this->checkNightClosing();
        if ($closed) {
            return $closed;
        }

        $BookingCart = BookingCart::where('user_id', auth()->id())
            ->where('room_id', $request->room_id)
            ->where('check_in_date', $this->check_in_date)
            // ->where('check_out_date', $request->to_date)
            ->first();
        if ($BookingCart) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Success',
                'data'      => 'Room already booked !'
            ]);
        }

        $category = RoomCategory::where('id', $request->category_id)->first();


        $cart = BookingCart::firstOrCreate([
            'user_id'          => auth()->id(),
            'room_id'          => $request->room_id,
            'check_in_date'    => $this->check_in_date,
        ],[
            'check_out_date'    => $this->check_out_date,
            'nights'            => nightCount($this->check_in_date, $this->check_out_date),
            'guest'             => $request->guest ?? 1,
            'infant'            => $request->infant ?? 0,
            'room_category_id'  => $category->id,
            'category_price'    => $category->price,
        ]);



        if ($cart) {
            return response()->json([
                'status'    => 'Your request have been stored.',
                'data'      => $cart,
                'message'   => 'Success'
            ]);
        }
    }



    /*
     |--------------------------------------------------------------------------
     | CHECK ROOM AVAILABLE VIA DATE
     |--------------------------------------------------------------------------
     */

    public function checkNightClosing()
    {
        if (NightAuditSummary::query()->where('date', $this->check_in_date)->count() > 0) {
            return response()->json([
                'return'        => 0,
                'status'        => 'Unable to booked.',
                'message'       => 'Error',
            ]);

        }
    }


    /*
     |--------------------------------------------------------------------------
     | CHECK ROOM AVAILABLE VIA DATE
     |--------------------------------------------------------------------------
     */

    public function checkRoom($request)
    {
        $BookingCart = BookingCart::where('user_id', auth()->id())
            ->where('room_id', $request->room_id)
            ->where('check_in_date', $this->check_in_date)
            // ->where('check_out_date', $request->to_date)
            ->first();
        if ($BookingCart) {
            return response()->json([
                'status'    => 0,
                'message'   => 'Success',
                'data'      => 'Room already booked !'
            ]);
        }
    }
}
