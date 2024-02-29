<?php

namespace Module\Hotel\Services;

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
        $date = explode('-', $request->date);

        $this->check_in_date = $date[0];
        $this->check_out_date = trim($date[1]);
    }


    /*
     |--------------------------------------------------------------------------
     | BOOKING CART
     |--------------------------------------------------------------------------
    */

    public function store($request)
    {
        $this->checkNightClosing();

        $BookingCart = BookingCart::where('room_id', $request->room_id)
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
            'room_id'           => $request->room_id,
            'check_in_date'     => $this->check_in_date,
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
    public function remove($room_id)
    {
       $cart = BookingCart::query()->where('room_id', $room_id)->delete();
       if($cart){
        return response()->json([
            'status'    => 1,
            'data'      => 'Success',
            'message'   => 'Success',
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
        $BookingCart = BookingCart::where('room_id', $request->room_id)
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
