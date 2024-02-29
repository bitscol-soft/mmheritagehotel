<?php

namespace Module\BanquetHall\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use Module\HotelService\Models\Service\HotelService;

class AjaxController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | GUEST METHOD
     |--------------------------------------------------------------------------
    */

    public function guestList(Request $request)
    {

        return Guest::query()
                        ->where(function($q) use($request) {
                            $q->where('name', 'like', $request->search . '%')
                            ->orWhere('phone_no', 'like', '%' . $request->search . '%')
                            ->orWhereHas('booking', function($q){
                                $q->whereHas('roomNumber', function($query){
                                    $query->where('room_number', request('search'));
                                })->where('status', 1);
                            })->where('status', 1);
                        })
                        ->when(request()->filled('bar'), fn($q) => $q->where('is_bar', request('bar')))
                        ->with('booking')
                        ->take(25)
                        ->get();
    }

    public function roomList(Request $request)
    {

        return Rooms::query()->where('room_number', 'LIKE', '%' . $request->name . '%')->get();
    }


    public function getDetailsByname($id)
    {
        $guest = Guest::with('bookingList')->find($id);

        if ($guest != null && $guest->booking_id == !null) {

            $bk_info    = $guest->bookingList->room_id;
            $bk_number  = $guest->bookingList->bookingInfo->booking_number;
            $room_info  = Rooms::where('id', $bk_info)->pluck('room_number')->first();

            $response['room_number']    = $room_info;
            $response['booking_number'] = $bk_number;
            $response['booking_id']     = $guest->booking_id;
            $response['guest_name']     = $guest->name;
            $response['guest_id']       = $guest->id;

        }
        else {
            $response['status'] = 0;
        }

        return $response;
    }



    public function getInfoByBooking($id)
    {
        $booking = Booking::find($id);
        $check_booking = $booking->guestInfo->booking_id;


        if ($check_booking == !null) {
            $guest = $booking->guestInfo;
            $guest_id = $guest->id;
            $guest_name = $guest->name;
            $room_number = $booking->bookingDetail->roomNumber->room_number;

            $response['guest_name']  = $guest_name;
            $response['guest_id']    = $guest_id;
            $response['room_number'] = $room_number;
        } else {
            $response['status'] = '0';
        }
        return $response;
    }

    public function getInfoByRoom($id)
    {

        $room = Rooms::with('booking_details.bookingInfo')->find($id);

        $check_booking = $room->booking_detail->bookingInfo->guestInfo;
        $booking = $room->booking_details->where('status',1)->first()->bookingInfo;

        if ($booking->status == 0 || $booking->status == 1 || $booking->status == 2) {

            $guest = optional($booking)->guestInfo;

            $response['status']         = 1;
            $response['guest_name']     = $guest->name;
            $response['guest_id']       = $guest->id;
            $response['booking_number'] = $booking->booking_number;
            $response['booking_id']     = $booking->id;

        } else {
            $response['status']         = 0;
        }

        return $response;
    }


    public function bookingNoList(Request $request)
    {
        return Booking::query()->where('booking_number', 'LIKE', '%' . $request->name . '%')->get();
    }


    /*
     |--------------------------------------------------------------------------
     | SERVICE METHOD
     |--------------------------------------------------------------------------
    */
    public function getService(Request $request)
    {
        return HotelService::where('name', 'like', '%' . $request->name . '%')->get();
    }
}
