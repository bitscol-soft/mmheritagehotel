<?php

namespace Module\Hotel\Services;

use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\Rooms;

class RoomStatusService
{
    public $room_status;



    public function availableRoom($check_in, $check_out)
    {

        return RoomCategory::with(['rooms' => function ($q) use ($check_in, $check_out) {

            $q->with(['booking_dates' => function ($qr) use ($check_in, $check_out) {
                $qr->where(function ($q) use ($check_in, $check_out) {
                    $q->where('date', '>=', $check_in)
                        ->where('date', '<=', $check_out);
                })
                    // ->whereHas('booking', function ($qr) {
                    //     $qr->where('status', '1')
                    //     ->orWhere('status', '0');
                    // })
                    // ->limit(1)
                    ->with('booking.guestInfo');
            }])

                ->withCount(['booking_dates as is_booked' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 2);
                    });
                }])
                ->withCount(['booking_dates as is_checkin' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 1);
                    });
                }])
                ->withCount(['booking_dates as is_reservation' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 0);
                    });
                }])
                ->withCount(['booking_dates as today_checkout' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out);
                    })->whereHas('booking', function ($qr) use($check_in) {
                        $qr->where('check_out_date', $check_in)->where('status', 1);
                    });
                }]);

                // ->with('booking_details.bookingInfo');
                // $q->with('booking_details.bookingInfo');
                // $q->withCount(['booking_details.bookingInfo as is_check_out_today' => function ($qr) use ($today) {
                //     $qr->whereDate('check_out_date', $today);
                // });
        }])->get();
    }



    public function availableRoomByCategory($check_in, $check_out, $category_id)
    {

        return Rooms::where('room_category', $category_id)
                    ->with(['booking_dates' => function ($qr) use ($check_in, $check_out) {
                        $qr->where(function ($q) use ($check_in, $check_out) {
                            $q->where('date', '>=', $check_in)
                                ->where('date', '<=', $check_out);
                        });
                    }])
                    ->withCount(['booking_dates as is_booked' => function ($qr) use ($check_in, $check_out) {
                        $qr->where(function ($q) use ($check_in, $check_out) {
                            $q->where('date', '>=', $check_in)
                                ->where('date', '<=', $check_out)
                                ->where('status', 2);
                        });
                    }])
                    ->withCount(['booking_dates as is_checkin' => function ($qr) use ($check_in, $check_out) {
                        $qr->where(function ($q) use ($check_in, $check_out) {
                            $q->where('date', '>=', $check_in)
                                ->where('date', '<=', $check_out)
                                ->where('status', 1);
                        });
                    }])
                    ->withCount(['booking_dates as is_reservation' => function ($qr) use ($check_in, $check_out) {
                        $qr->where(function ($q) use ($check_in, $check_out) {
                            $q->where('date', '>=', $check_in)
                                ->where('date', '<=', $check_out)
                                ->where('status', 0);
                        });
                    }])
                    ->withCount(['booking_dates as today_checkout' => function ($qr) use ($check_in, $check_out) {
                        $qr->where(function ($q) use ($check_in, $check_out) {
                            $q->where('date', '>=', $check_in)
                                ->where('date', '<=', $check_out);
                        })->whereHas('booking', function ($qr) use($check_in) {
                            $qr->where('check_out_date', $check_in)->where('status', 1);
                        });
                    }])
                    ->where('status', 1)->get();
    }

    public function BookedRoom($check_in, $check_out)
    {

        return Rooms::with(['booking_dates' => function ($qr) use ($check_in, $check_out) {
            $qr->where(function ($q) use ($check_in, $check_out) {
                $q->where('date', '>=', $check_in)
                    ->where('date', '<=', $check_out);
            });
                // ->whereHas('booking', function ($qr) {
                //     $qr->where('status', '1')
                //     ->orWhere('status', '0');
                // })
                // ->limit(1)
                // ->with('booking.guestInfo');
        }])
            // ->withCount(['booking_dates as is_booked' => function ($qr) use ($check_in, $check_out) {
            //     $qr->where(function ($q) use ($check_in, $check_out) {
            //         $q->where('date', '>=', $check_in)
            //             ->where('date', '<=', $check_out)
            //             ->where('status', 2);
            //     });
            // }])
            ->withCount(['booking_dates as is_checkin' => function ($qr) use ($check_in, $check_out) {
                $qr->where(function ($q) use ($check_in, $check_out) {
                    $q->where('date', '>=', $check_in)
                        ->where('date', '<=', $check_out)
                        ->where('status', 1);
                });
            }])
            // ->withCount(['booking_dates as is_reservation' => function ($qr) use ($check_in, $check_out) {
            //     $qr->where(function ($q) use ($check_in, $check_out) {
            //         $q->where('date', '>=', $check_in)
            //             ->where('date', '<=', $check_out)
            //             ->where('status', 0);
            //     });
            // }])
            // ->withCount(['booking_dates as today_checkout' => function ($qr) use ($check_in, $check_out) {
            //     $qr->where(function ($q) use ($check_in, $check_out) {
            //         $q->where('date', '>=', $check_in)
            //             ->where('date', '<=', $check_out);
            //     })->whereHas('booking', function ($qr) use ($check_in) {
            //         $qr->where('check_out_date', $check_in)->where('status', 1);
            //     });
            // }])
            ->get();

    }
}
