<?php

namespace Module\Hotel\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\Rooms;

class RoomService
{

    public $request;







    public function checkRoomForBooking()
    {
        $request = $this->request = \request();


        $check_in   = $request->check_in_date;
        $check_out  = $request->check_out_date;
        $room_id    = $request->room_id;


        return Rooms::with('roomCategory')->withCount(['booking_dates as is_booked' => function ($qr) use ($check_out) {
                        $qr->where('date', $check_out)->whereIn('status', [1,2]);
                        }])->withCount(['booking_dates as is_reservation' => function ($qr) use ($check_out) {
                            $qr->where('date', $check_out)->where('status', 0);
                        }])->where('id', $room_id)->get()->map(function($item){
                            return [

                                'is_booked'         => $item->is_booked,
                                'is_reservation'    => $item->is_reservation,
                            ];
                        });


    }





    public function getAvailableRoom()
    {
        $request = $this->request = \request();


        $check_in   = $request->check_in_date;
        $check_out  = $request->check_out_date;
        $room_id    = $request->room_id;


        $room = Rooms::find($room_id);

        //--------- OLD QUERY BY ROOMS ---------//
        // $data['available_rooms'] = Rooms::with('roomCategory')->whereDoesntHave('booking_dates', function ($qr) use ($check_out) {
        //         $qr->where('date', '>=', $check_out)
        //             ->whereDoesntHave('booking', function ($qr) {
        //                 $qr->where('status', 1);
        //             });
        //     })
        //     ->whereDoesntHave('booking_dates', function ($qr) use ($check_out) {
        //         $qr->where('date', '>=', $check_out)
        //             ->whereDoesntHave('booking', function ($qr) {
        //                 $qr->where('status',0);
        //             });
        //     })
        //     // ->where('room_category', $room->room_category)
        //     ->where('status', 1)
        //     ->get();

        $data['roomCategories'] = RoomCategory::with(['rooms' => function($query) use($check_out){
                                                    $query->where('status',1)
                                                        ->whereDoesntHave('booking_dates', function ($qr) use ($check_out) {
                                                            $qr->where('date', '>=', $check_out)
                                                                ->whereDoesntHave('booking', function ($qr) {
                                                                    $qr->where('status', 1);
                                                                });
                                                        })
                                                        ->whereDoesntHave('booking_dates', function ($qr) use ($check_out) {
                                                            $qr->where('date', '>=', $check_out)
                                                                ->whereDoesntHave('booking', function ($qr) {
                                                                    $qr->where('status',0);
                                                                });
                                                        });
                                                }])
                                                ->where('status', 1)->get();

        return view('booking.adjust._inc.available-room', $data)->render();


    }
}
