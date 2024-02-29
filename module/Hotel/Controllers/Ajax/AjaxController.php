<?php

namespace Module\Hotel\Controllers\Ajax;

use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Module\CRM\Models\CRMCustomer;
use App\Http\Controllers\Controller;
use Module\Bar\Models\RstTableManage;
use Module\Hotel\Services\RoomService;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\NightAuditSummary;
use Module\Hotel\Models\BookingMemberDetail;
use Module\Hotel\Services\RoomStatusService;
use Module\Hotel\Services\BookingCartService;

class AjaxController extends Controller
{



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function roomCheck(Request $request)
    {
        return (new BookingCartService())->checkRoom($request);
    }


    /*
     |--------------------------------------------------------------------------
     | bookingCartStore
     |--------------------------------------------------------------------------
    */
    public function bookingCartStore(Request $request)
    {
        return (new BookingCartService())->store($request);
    }




    /*
     |--------------------------------------------------------------------------
     | bookingCartStore
     |--------------------------------------------------------------------------
    */
    public function checkRoomForBooking(Request $request)
    {
        return (new RoomService())->checkRoomForBooking($request);
    }




    /*
     |--------------------------------------------------------------------------
     | BOOKING MEMBER DETAIL
     |--------------------------------------------------------------------------
    */
    public function getBookingMemberDetail(Request $request)
    {
        $members = BookingMemberDetail::where('booking_id', $request->booking_id)->get();

        return view('booking/_inc/member-detail-item', compact('members'))->render();
        return (new RoomService())->checkRoomForBooking($request);
    }


    /*
     |--------------------------------------------------------------------------
     | bookingCartStore
     |--------------------------------------------------------------------------
    */
    public function getAvailableRoom(Request $request)
    {
        return (new RoomService())->getAvailableRoom($request);
    }


    /*
     |--------------------------------------------------------------------------
     | GET TABLE
     |--------------------------------------------------------------------------
    */
    public function getTable(Request $request)
    {
        return RstTableManage::query()->when($request->filled('name'), fn($q)=> $q->where('name', 'LIKE', $request->name .'%'))->take(25)->get();
    }





    public function checkNightAudit()
    {
        try {
           $count = NightAuditSummary::query()->where('date', request('check_in_date'))->count();

           return response()->json([
            'status'    => 1,
            'data'      => $count,
            'message'   => 'Success',
           ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'data'      => [],
                'message'   => $th->getMessage(),
            ]);
        }
    }



     /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET IN HOUSE GUEST INFORMATION
     * ---------------------------------------------------------------------
     **/

    public function inHouseGuestInformation(Request $request)
    {
        try {

            $room_number = Rooms::where('id', $request->room_id)->first();

            $booking_details = BookingDetails::where('room_id', $room_number->id)->where('status', 1)->with('bookingInfo.customer')->first();

            if ($booking_details) {
                return optional($booking_details->bookingInfo)->customer;

            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'data'      => [],
                'message'   => $th->getMessage(),
            ]);
        }
    }


     /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET HOTEL GUEST INFORMATION
     * ---------------------------------------------------------------------
     **/

    public function HotelGuestInformation(Request $request)
    {
        try {

            $room_number = Rooms::where('id', $request->room_id)->first();

            $booking_details = BookingDetails::where('room_id', $request->room_id)
                                                ->with('bookingInfo', function($q){
                                                    $q->where('status', 2);
                                                })
                                                ->with('bookingInfo.customer')->latest()->first();

            if ($booking_details) {
                $customer = optional($booking_details->bookingInfo)->customer;

                return response()->json([
                    'status'   => 'success',
                    'customer' => $customer,
                    'booking'  => $booking_details,

                ]);


            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => 0,
                'data'      => [],
                'message'   => $th->getMessage(),
            ]);
        }
    }


    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET GuestData
     * ---------------------------------------------------------------------
     **/

     public function GetGuestData(Request $request){
        return Guest::query()
            ->where(function($q) use($request) {
                $q->where("name", "like", "%{$request->search}%")
                    ->orWhere("name", "like", "%{$request->search}")
                    ->orWhere("name", "like", "{$request->search}%")
                    ->orWhere("phone_no", "like", "{$request->search}%");
            })
            ->take(25)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->name,
                    'phone_no'      => $item->phone_no,
                ];
            });
    }



    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET GuestData
     * ---------------------------------------------------------------------
     **/

     public function GetCrmCompany(Request $request){
        return CRMCustomer::query()
            ->where(function($q) use($request) {
                $q->where("org_name", "like", "%{$request->search}%")
                    ->orWhere("org_name", "like", "%{$request->search}")
                    ->orWhere("c_name", "like", "{$request->search}%")
                    ->orWhere("org_phone", "like", "{$request->search}%");
            })
            ->take(25)
            ->orderBy('org_name', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'name'          => $item->org_phone,
                    'phone_no'      => $item->org_phone,
                ];
            });
    }


    /**
     * ---------------------------------------------------------------------
     * AJAX METHODS - GET BOOKING DETAILS
     * ---------------------------------------------------------------------
     **/

     public function getBookingDetails(Request $request){
        $booking= Booking::latest()
                            ->with('bookingDetails.roomCategory', 'bookingDetails.roomNumber', 'guestInfo:id,name,phone_no','transection','bookingExtraCharge','booking_members','guestImage')
                            ->with('transection', function($q){
                                $q->with('extraCharge_ledger');
                            })->find($request->id);

        return response()->json($booking);
    }
}
