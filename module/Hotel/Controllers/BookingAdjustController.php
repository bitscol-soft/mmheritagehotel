<?php

namespace Module\Hotel\Controllers;

use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Services\BookingAdjustService;
use Module\Hotel\Services\BookingAdjustServiceV2;

class BookingAdjustController extends Controller
{
    private $service;


    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->service = new BookingAdjustServiceV2();
    }












    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        // adjustments always need a booking context; the UI links here without one
        return redirect()->route('booking.index')->with('info', 'Open a booking to adjust it.');
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $data['booking']        = Booking::with('bookingDetails.roomNumber', 'bookingAdjusts')->with('hotel_transaction', function($q){
                                                $q->where('source_type', 'Booking');
                                            })->find($request->booking_id);

        if ($data['booking'] === null) {
            return redirect()->route('booking.index')->with('error', 'Select a booking first to make adjustments.');
        }

        $data['account_types']  = AccountType::where('status', 1)->pluck('name', 'id');


        // $check_out              = date('Y-m-d', strtotime("+1 day", strtotime($data['booking']->check_out_date)));
        $check_out              = $data['booking']->check_out_date;


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


        return view('booking.adjust.create', $data);
    }













    /*
     |--------------------------------------------------------------------------
     | STORE METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        // return redirect()->back()->withError('Migration is processing...');

        try {

            DB::transaction(function() use($request) {

                $this->service->updateBooking($request);
                $this->service->updateBookingDetail($request);
                $this->service->transaction($request);

            });

        } catch (\Throwable $th) {

            // throw $th;

            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->route('booking.index')->with('success', 'Booking Adjust Successfully !');
    }













    /*
     |--------------------------------------------------------------------------
     | SHOW METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        # code...
    }













    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD
     |--------------------------------------------------------------------------
    */
    public function update($id, Request $request)
    {
        # code...
    }












    /*
     |--------------------------------------------------------------------------
     | DELETE/DESTORY METHOD
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        # code...
    }
}
