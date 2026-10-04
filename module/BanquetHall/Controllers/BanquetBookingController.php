<?php

namespace Module\BanquetHall\Controllers;

use Exception;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use Module\Hotel\Models\Guest;
use Module\Hotel\Models\Rooms;
use App\Services\ExportService;
use Illuminate\Validation\Rule;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\DB;
use Module\CRM\Models\CRMCustomer;
use Module\Hotel\Models\Booking; // round-8: getInvoice() referenced Booking without import -> 500 on /BanquetHall/invoice/{id}
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\BookingNote;
use Module\Hotel\Models\RoomCategory;
use Module\Restaurant\Models\Product;
use Module\Hotel\Models\BookingPurpose;
use Module\Hotel\Models\HotelTransection;
use Module\BanquetHall\Models\BanquetRoom;
use Module\BanquetHall\Models\BanquetBooking;
use Module\BanquetHall\Models\BanquetCategory;
use Module\BanquetHall\Services\BookingService;

class BanquetBookingController extends Controller{



    use SendNotification;

    private $service;
    private $export_service;



    /**
     * -----------------------------------------------------------
     * CONSTRUCT METHOD
     * -----------------------------------------------------------
     */
    public function __construct()
    {
        $this->service          = new BookingService();
        $this->export_service   = new ExportService();
    }





    /*
     |--------------------------------------------------------------------------
     | Index METHOD FOR SHOW Booking List PAGE
     |--------------------------------------------------------------------------
    */
    public function index()
    {
        $this->hasAccess("bookings.index");


        $data['category']    = RoomCategory::roomName();

        $data['booking']     = BanquetBooking::latest()
                                        ->searchByField('booking_date')
                                        ->searchByField('check_in_date')
                                        ->searchByField('check_out_date')
                                        ->searchDateFrom('booking_date','booking_from_date')
                                        ->searchDateTo('booking_date','booking_to_date')
                                        // ->when(request()->filled('booking_to'), function ($qr) {
                                        //     $qr->where('booking_date', '<=', (request('booking_to')));
                                        // })
                                        ->searchByField('customer_id')
                                        ->searchByField('status')
                                        ->likeSearch('booking_number')
                                        ->likeSearch('reference')
                                        ->with('guestInfo')
                                        ->with('transection', function($q){
                                            $q->with('extraCharge_ledger');
                                        })
                                        ->with('transection.source')
                                        ->searchFromRelation('details', 'room_id');

        $data['guest']       = Guest::where('status', 1)->get();

        if(request('export_type')){

            $data['booking']      = $data['booking']->get();
            $data['paginate']     = 0;

            $data['company']      = Company::first();

            return $this->export_service->exportData($data, 'hall_booking/export/', 'Booking Report');
        }

            $data['booking']      = $data['booking']->paginate(30);

            $data['account_types']      = AccountType::where('status', 1)->get();

        // return $data['booking'];
        return view('hall_booking/index', $data);

    }




    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD
     |--------------------------------------------------------------------------
    */
    public function create()
    {
        $this->hasAccess("bookings.create");
        $data['countries']      = Country::pluck('name', 'id');
        $data['rooms']          = BanquetRoom::get();
        $data['guest']          = Guest::where('status', 1)->get();
        $data['booking_purpose']= BookingPurpose::active()->get();
        $data['vat']            = Vat::first();
        $data['account_types']  = AccountType::where('status', 1)->pluck('name', 'id');
        $data['products']       = Product::query()->notPackage()->NotMaterial()->NotBar()
                                        // ->with('category', 'unit', 'pack_unit', 'supplier')
                                        // ->likeSearch('name')
                                        // ->searchByField('barcode')
                                        // ->searchByField('category_id')
                                        // ->where('is_bar', 0)
                                        ->get();


        $data['crmCompanies']   = CRMCustomer::latest()->get();

        return view('hall_booking.create', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | store METHOD FOR Save New Booking
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
                'booking_date'          => 'required',
                'customer_id'           => 'required',
            ]);
        try {

            DB::transaction(function () use ($request) {

                // STORE BOOKING AND TRANSACTION
                $this->service->store($request);

                // SET NEXT INVOICE NO
                $this->service->setNextInvoiceNo('Hall Booking', date('Y-m'));

                // SAVE BOOKING DETAILS
                $this->service->saveBookingDetails($request, $this->service->booking->id);

                $this->service->saveProductDetails($request, $this->service->booking->id);

                $this->service->saveItemDetails($request, $this->service->booking->id);


                //--------- SEND SMS TO GUEST ---------//
                // if (isset($request->sms)) {
                //     $messages = 'Your booking have been successfully Reserved. Booking No. '.$this->service->booking->booking_number;
                //     $this->sendSmsNotification($messages , $guest->phone_no);
                // }


                //-------- SEND EMAIL TO GUEST --------//
                // if (isset($request->email)) {
                //     $this->sendEmailNotification($this->service->booking->id, $guest->email);
                // }


                // Save member Details
                // $this->service->saveMemberDetails($request);

                // $this->removeAllCookieItems();

            });

            return redirect()->route('banquet.booking.index')->with([
                'fromBookingStore'  => 'Yes',
                'bookingId'         => $this->service->booking->id,
                'success'           => 'Booking have been successfully Reserved',
                'message'           => 'Booking have been successfully Reserved',
            ]);


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        // return redirect()->route('generate.invoice-v2', ['id' => $this->service->booking->id])->withMessage('Booking have been successfully Reserved !');

    }



    /*
     |--------------------------------------------------------------------------
     | EDIT METHOD
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $this->hasAccess("bookings.edit");

        $data['booking']        = Booking::with('transection')->find($id);
        $data['guest']          = Guest::where('status', 1)->pluck('name', 'id');
        $data['guests']         = Guest::where('status', 1)->get();
        $data['rooms']          = Rooms::roomNumber();
        $data['categories']     = RoomCategory::with('roomPrices')->get();
        $data['booking_purpose']= BookingPurpose::active()->get();
        $data['account_types']  = AccountType::where('status', 1)->pluck('name', 'id');
        $data['countries']      = Country::pluck('name', 'id');

        $data['crmCompanies']   = CrmCustomer::where('org_name','!=', null)
                                            //  ->where('is_customer', 1)
                                             ->select('id','org_name','org_phone','org_email','address')
                                             ->get();


        return view('booking/edit', $data);
        // return view('booking/assaign', $data);
    }






    /*
     |--------------------------------------------------------------------------
     | Show METHOD
     |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $this->hasAccess("bookings.view");

        $data['booking']        = $booking = Booking::with('transection', 'hotelServiceSale.transactions', 'resturentServiceSale.RstTransactions', 'booking_members')->find($id);
        $data['account_type']   = AccountType::where('status', 1)->pluck('name', 'id');

        $data['transactions']  = HotelTransection::with('source')
                                                    ->where('booking_id', $id)
                                                    ->where(function($q){
                                                        $q->where('due_amount', '>', 0)
                                                            ->orWhere(function($q){
                                                            $q->where('due_amount', '>', 0)
                                                                ->where('source_type', 'Restaurant Sale');
                                                        });
                                                    })
                                                    ->get();

        $data['total_night']    = Carbon::parse($booking->check_out_date)->diffInDays(Carbon::parse($booking->check_in_date));

        return view('booking/view', $data);
    }




    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD FOR Update Booking Information
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id'       => 'required',
            // 'purpose'           => 'required',
            'room_category.*'   => 'required',
            'room_number.*'     => 'required',
            // 'guest'       => 'required',
        ],[
            'customer_id.required'   => 'The Customer field  is required!',
            'room_category.*.required' => 'The Room Category field is required!',
            'room_number.*.required'   => 'The Room Number field is required!',
        ]);

        try {

            DB::transaction(function () use ($request, $id) {

                $booking = Booking::find($id);

                // UPDATING BOOKING, TRANSACTION
                $this->service->update($request, $booking);


                // UPDATE BOOKING DETAILS
                $this->service->updateBookingDetails($request, $this->service->booking->id);

                // UPDATE GUEST
                $guest = Guest::find($booking->customer_id);
                $guest->update([
                    'company_id'    => $request->company_id
                ]);


                //--------- SEND SMS TO GUEST ---------//
                if (isset($request->sms)) {
                    $messages = 'Your booking have been successfully Reserved. Booking No. '.$this->service->booking->booking_number;
                    $this->sendSmsNotification($messages , $guest->phone_no);
                }


                //-------- SEND EMAIL TO GUEST --------//
                if (isset($request->email)) {
                    $this->sendEmailNotification($booking->id, $guest->email);
                }


                // UPDATE BOOKING DETAILS OLD FROM MAKSUD VAI
                // $this->service->updateBookingDetails($request, $booking);


            });

            return redirect()->route('booking.index')->with('message', 'Booking have been updated success!');

        } catch (\Throwable $e) {
            // throw $e;
            return redirect()->back()->with('error', $e->getMessage());
        }

    }




    /*
     |--------------------------------------------------------------------------
     | destroy METHOD FOR Delete Booking Information
     |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {

        $this->hasAccess("bookings.delete");

        try {

            $this->service->deleteBooking($id);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', 'You cannot delete this booking!!' . $e->getMessage());
        }
        return redirect()->back()->with('message', 'Booking have been deleted success!');


    }




    /*
     |--------------------------------------------------------------------------
     | ASSIGN ROOM METHOD FOR Booking Information
     |--------------------------------------------------------------------------
    */
    // public function assign(Request $request, $id)
    // {
    //     // dd($request->all(), $id);
    //     $request->validate([
    //         'customer_id'       => 'required',
    //         // 'purpose'           => 'required',
    //         'room_category.*'   => 'required',
    //         'room_number.*'     => 'required',
    //         // 'guest'       => 'required',
    //     ],[
    //         'customer_id.required'   => 'The Customer field  is required!',
    //         'room_category.*.required' => 'The Room Category field is required!',
    //         'room_number.*.required'   => 'The Room Number field is required!',
    //     ]);

    //     try {

    //         DB::transaction(function () use ($request, $id) {

    //             $booking = Booking::find($id);

    //             // UPDATING BOOKING, TRANSACTION
    //             $this->service->assign($request, $booking);
    //             // dd('ok');


    //             // UPDATE BOOKING DETAILS
    //             $this->service->assignBookingDetails($request, $this->service->booking->id);


    //             // UPDATE GUEST
    //             $guest = Guest::find($booking->customer_id);
    //             $guest->update([
    //                 'company_id'    => $request->company_id
    //             ]);


    //             //--------- SEND SMS TO GUEST ---------//
    //             if (isset($request->sms)) {
    //                 $messages = 'Your booking have been successfully Reserved. Booking No. '.$this->service->booking->booking_number;
    //                 $this->sendSmsNotification($messages , $guest->phone_no);
    //             }


    //             //-------- SEND EMAIL TO GUEST --------//
    //             if (isset($request->email)) {
    //                 $this->sendEmailNotification($booking->id, $guest->email);
    //             }




    //         });

    //         return redirect()->route('booking.index')->with('message', 'Booking have been updated success!');

    //     } catch (\Throwable $e) {
    //         // throw $e;
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }

    // }



    /*
     |--------------------------------------------------------------------------
     | getInvoice METHOD FOR Generate Booking Confirmation Invoice after Check-IN
     |--------------------------------------------------------------------------
    */
    public function getInvoice($id)
    {
        $booking = Booking::with('transection', 'bookingDetails.bookingTransection', 'hotelServiceSale.transactions', 'resturentServiceSale.RstTransactions','bookingAdjusts.transactions')->find($id);

        $company = Company::first();

        return view('booking.checkout_invoice', compact('booking', 'company'));
    }

    /*
     |--------------------------------------------------------------------------
     | getInvoice METHOD FOR Generate Booking Confirmation Invoice after Check-IN
     |--------------------------------------------------------------------------
    */
    public function getInvoiceV2($id)
    {
        $data['transactions'] = HotelTransection::with('source')
                                                ->where('source_type', 'Hall Booking')
                                                ->where('source_id', $id)->get();

        $data['booking']      = BanquetBooking::with('bookingDetails', 'ItemDetails', 'ProductDetails', 'getVat', 'paymentType')->find($id);


        //    $data['booking']      = Booking::with(['bookingDetails' => function($q){
        //                                             $q->with('roomNumber')->with('roomCategory');
        //                                         }])
        //                                         ->with('bookingExtraCharge','guestInfo', 'categoryName.roomNumber', 'bookingAdjusts:id,booking_id')
        //                                         ->with('transection', function($q){
        //                                             $q->with('extraCharge_ledger');
        //                                         })
        //                                         ->with('getVat')->with('paymentType')->find($id);



        $data['company'] = Company::first();

        return view('hall_booking.checkout-invoice-v3', $data);


    }





    //--------------------------------------------------------------------------//
    //                    CONFIRM RESERVATION INVOICE METHOD                    //
    //--------------------------------------------------------------------------//
    public function reservationInvoice($id)
    {
        $data['transactions'] = HotelTransection::with('source')->where('booking_id', $id)->get();
        $data['booking']      = BanquetBooking::with('bookingDetails')
                                            ->with('getVat', 'booking_purpose')
                                            ->find($id);

        $data['company'] = Company::first();

        $data['bookingNotes'] = [];
        // $data['bookingNotes'] = BookingNote::where('status', 1)->get();

        return view('hall_booking.reservation-invoice', $data);
    }










    /*
     |--------------------------------------------------------------------------
     | checkoutInvoice METHOD FOR Generate Booking Checkout Invoice after Check-OUT
     |--------------------------------------------------------------------------
    */
    public function checkoutInvoice($id)
    {

        $booking    = BanquetBooking::with('transection', 'hotelServiceSale.transactions', 'resturentServiceSale.RstTransactions','bookingAdjusts.transactions')->find($id);


        $transection = HotelTransection::whereIn('source_type', ['Booking', 'Booking Adjust'])->where('source_id', $id)->first();
        $company     = Company::first();

        return view('hall_booking.checkout_invoice', compact('booking', 'company', 'transection'));
    }








    /*
     |--------------------------------------------------------------------------
     | getRooms METHOD FOR Showing rooms list by Room category via AJAX Call
     |--------------------------------------------------------------------------
    */
    public function getRooms(Request $request, $id)
    {

        $category = BanquetRoom::find($id);

        $room = (new RoomStatusService())->availableRoomByCategory($request->check_in_date, $request->check_out_date, $request->category_id);



        if($request->bulk_booking == 1){
            $data['rooms'] = '';
        }else{
            $data['rooms'] = '<option value="">Select Room Number</option>';
        }


        $data['total_room'] = $room->count();

        if ($room) {
            $totalRoom = 0;

            foreach ($room as $value) {
                if ($value->is_booked == 0 && $value->is_checkin == 0 && $value->is_reservation == 0) {
                    $totalRoom = $totalRoom + 1;
                    $data['rooms'] .= '<option value="' . $value->id . '">Room ' . $value->room_number . '</option>';

                }
            }

            if($request->bulk_booking == 1){
                $data['total_room'] = $totalRoom;
            }


            $data['guests']     = '';


            if (setting('root_currency') == 141) {
                for ($i=1; $i <= $category->guest_capacity ?? 1; $i++) {

                    if ($category->allow_guest_wise_price == 1) {
                        $price = calculateCurrencyAmount(optional(optional($category->roomPrices)->where('capacity', $i)->first()),1);
                    }
                    else{
                        $price = calculateCurrencyAmount($category->price,1);
                    }
                    $data['guests'] .= '<option value="'. $i . '" data-price="'.$price.'">'. $i . '</option>';
                }

                $data['price']      =  calculateCurrencyAmount($category->price,1);

            } else {
                for ($i=1; $i <= $category->guest_capacity ?? 1; $i++) {

                    if ($category->allow_guest_wise_price == 1) {
                        $price = optional(optional($category->roomPrices)->where('capacity', $i)->first())->price;
                    }
                    else{
                        $price = $category->price;
                    }
                    $data['guests'] .= '<option value="'. $i . '" data-price="'.$price.'">'. $i . '</option>';
                }

                $data['price']      =  $category->price;
            }


            $data['max_guest']  = $category->guest_capacity;
        } else {

            $data .= '<option value="">No data found</option>';
        }
        return $data;
    }



    /*
     |--------------------------------------------------------------------------
     | getRooms METHOD FOR Showing rooms list by Room category via AJAX Call
     |--------------------------------------------------------------------------
    */
    public function getRoomsForBooking(Request $request)
    {

            // "rent": null,
            // "beds": null,
            // "max_guests": null

        $room = BanquetRoom::latest()->get();

        // $room = (new RoomStatusService())->availableRoomByCategory($request->check_in_date, $request->check_out_date, $request->category_id);



        // if($request->bulk_booking == 1){
        //     $data['rooms'] = '';
        // }else{
            $data['rooms'] = '<option value="">Select Room Number</option>';
        // }


        $data['total_room'] = $room->count();

        if ($room) {
            $totalRoom = 0;

            foreach ($room as $value) {
                // if ($value->is_booked == 0 && $value->is_checkin == 0 && $value->is_reservation == 0) {
                    $totalRoom = $totalRoom + 1;
                    $data['rooms'] .= '<option value="' . $value->id . '" data-room-price="'.$value->price.'">Room ' . $value->hall_number . '</option>';

                // }
            }

            // if($request->bulk_booking == 1){
            //     $data['total_room'] = $totalRoom;
            // }


            $data['guests']     = '';


            // if (setting('root_currency') == 141) {
            //     for ($i=1; $i <= $category->guest_capacity ?? 1; $i++) {

            //         if ($category->allow_guest_wise_price == 1) {
            //             $price = calculateCurrencyAmount(optional(optional($category->roomPrices)->where('capacity', $i)->first()),1);
            //         }
            //         else{
            //             $price = calculateCurrencyAmount($category->price,1);
            //         }
            //         $data['guests'] .= '<option value="'. $i . '" data-price="'.$price.'">'. $i . '</option>';
            //     }

            //     // $data['price']      =  calculateCurrencyAmount($category->price,1);

            // } else {
            //     for ($i=1; $i <= $category->guest_capacity ?? 1; $i++) {

            //         if ($category->allow_guest_wise_price == 1) {
            //             $price = optional(optional($category->roomPrices)->where('capacity', $i)->first())->price;
            //         }
            //         else{
            //             $price = $category->price;
            //         }
            //         $data['guests'] .= '<option value="'. $i . '" data-price="'.$price.'">'. $i . '</option>';
            //     }

            //     // $data['price']      =  $category->price;
            // }


            // $data['max_guest']  = $category->guest_capacity;
        } else {

            $data .= '<option value="">No data found</option>';
        }
        return $data;
    }








    /*
     |--------------------------------------------------------------------------
     | getSearch METHOD FOR Search Booking Information
     |--------------------------------------------------------------------------
    */
    public function getSearch(Request $request)
    {

        $category = RoomCategory::roomName();
        $guest    = Guest::where('status', 1)->get();
        $booking  = BanquetBooking::where('booking_date', $request->booking_search_date)
            ->orwhere('customer_id', $request->customer_id)
            ->orwhere('check_in_date', $request->booking_search_check_in)
            ->orwhere('check_out_date', $request->booking_search_check_out)
            ->orwhereHas('bookingList', function ($r) use ($request) {
                $r
                    ->where('category_id', $request->category)
                    ->where('room_id', $request->room_number);
            })->paginate(30);



        return view('booking.index', compact('booking', 'category', 'guest'));
    }





    /*
     |--------------------------------------------------------------------------
     | GET CHECK-IN METHOD FOR  CHECK-IN AFTER BOOKING
     |--------------------------------------------------------------------------
    */
    public function getCheckIn(Request $request, $id)
    {

        try {
            $booking = BanquetBooking::find($id);

            if (date('Y-m-d') >= $booking->check_in_date) {
                $booking->update([

                    'check_in_note' => $request->check_in_note,
                    'check_in_time' => now(),
                    'status'        => 1,
                ]);
                $booking->bookingDetails()->update([
                    'status'        => 1,
                ]);
            }

            if (request('is_ajax') == 1) {
                return response()->json([
                    'status'    => 1,
                    'data'      => 'Successfully checked in.',
                    'message'   => 'Success',
                ]);
            }
            return redirect()->back()->with('message', 'Check In update Successfull');
        } catch (\Throwable $e) {

            if (request('is_ajax') == 1) {

                return response()->json([
                    'status'    => 0,
                    'data'      => $e->getMessage(),
                    'message'   => 'Error',
                ]);

            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }





    /*
     |--------------------------------------------------------------------------
     | DUE COLLECTION WHEN GUEST CHECK IN
     |--------------------------------------------------------------------------
    */
    public function dueCollection(Request $request, $id)
    {

        try {

            DB::transaction(function () use ($id, $request) {

                $booking = BanquetBooking::with('bookingDetails', 'paymentType')->find($id);


                //----------- IT WILL CREATE THE TRANSACTION -----------//
                // $this->service->dueCollection($request, $booking);

                //----------- IT WILL UPDATE THE TRANSACTION -----------//
                $this->service->transaction($request, $booking, 'Hall Booking', $request->amount, $request->payment_type);


                // new
                // $this->service->DueTransaction($request, $booking, 'Booking', $request->amount, $request->payment_type);


            });

        } catch (Exception $e) {
            // throw $e;
            return redirect()->back()->with('error', $e->getMessage());
        }

        // return redirect()->back()->with('message', 'Collection have been submitted.');
        // return redirect()->route('generate.invoice-v2', $id)->with('message', 'Check out successfully completed.');
    }





    /*
     |--------------------------------------------------------------------------
     | EXTRA CHARGE METHOD
     |--------------------------------------------------------------------------
    */
    public function extraCharge(Request $request)
    {

        $request->validate([
            'extra_amount'              => 'required',
        ],[
            'extra_amount.required'     => 'Extra Charges Required!',
        ]);
        try {

            DB::transaction(function () use ($request) {

                $this->service->extraChargeV2($request);
            });

            return redirect()->route('booking.index')->with('message', 'Extra Charges have been applied!');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

    }


    /*
     |--------------------------------------------------------------------------
     | EXTRA CHARGE METHOD OLD
     |--------------------------------------------------------------------------
    */
    public function extraChargeOld(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $bookingExtraCharge = BookingExtraCharge::where('booking_id', $request->booking_id)->first();
                $hotelTransaction   = HotelTransection::where('booking_id' ,$request->booking_id)->first();
                $convertedAmount    = convertToBDTCurrency($request->extra_amount);


                if ($bookingExtraCharge != null) {

                    $total_amount = $hotelTransaction->total_amount + abs($bookingExtraCharge->extra_amount - $convertedAmount);

                    HotelTransection::where('booking_id' ,$request->booking_id)->update([
                        'extra_charge'      => $convertedAmount,
                        'total_amount'      => $total_amount,
                    ]);

                    $bookingExtraCharge->update([
                        'extra_amount'  => $convertedAmount,
                        'reason'        => $request->reason ?? $bookingExtraCharge->reason
                    ]);

                    if ($request->extra_amount > 0) {
                        HotelTransactionLedger::where('source_id', $hotelTransaction->source_id)
                                              ->where('hotel_transaction_id', $hotelTransaction->id)
                                              ->where('source_type', 'Booking')->where('remarks', 'Extra Charge')
                                              ->update([
                                                  'date'                  => date('Y-m-d'),
                                                  'in'                    => $request->extra_amount,
                                                  'payment_type'          => $request->payment_type,
                                                ]);
                    }

                } else {
                    BookingExtraCharge::create([
                        'booking_id'    => $request->booking_id,
                        'extra_amount'  => $convertedAmount,
                        'reason'        => $request->reason
                    ]);

                    $total_amount = $hotelTransaction->total_amount + $convertedAmount;

                    $hotelTransaction->update([
                        'extra_charge'      => $convertedAmount,
                        'total_amount'      => $total_amount,
                    ]);

                    if ($request->extra_amount > 0) {
                        HotelTransactionLedger::create([
                            'source_id'             => $hotelTransaction->source_id,
                            'source_type'           => 'Booking',
                            'payment_type'          => $request->payment_type,
                            'date'                  => date('Y-m-d'),
                            'hotel_transaction_id'  => $hotelTransaction->id,
                            'in'                    => $request->extra_amount,
                            'out'                   => 0,
                            'remarks'               => 'Extra Charge',
                        ]);
                    }

                }

            });

            return redirect()->route('booking.index')->with('message', 'Extra Charges have been applied!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }




    /*
     |--------------------------------------------------------------------------
     | bookingUi METHOD FOR Box Style Room Booking
     |--------------------------------------------------------------------------
    */
    /**
     * @deprecated since W3.8 (this method is not wired to any route in
     *             module/BanquetHall/routes/web_banquet_hall.php).
     *             It was originally the "box style room booking" UI, but
     *             the box-style flow is no longer in use. Kept for now
     *             in case an external client (webhook, scheduled task)
     *             still calls it via routing-by-reflection. Remove in
     *             W3.9 if no caller emerges.
     */
    public function bookingUi(Request $request)
    {
        $this->hasAccess("bookings.create");

        $check_in                   = date('Y-m-d');
        $check_out                  = date('Y-m-d', strtotime($check_in . "+1 days"));

        $data['mix_date']           = $check_in . ' - ' . $check_out;
        $data['booking_date']       = date('m/d/Y') . ' - ' . Carbon::now()->addDay()->format('m/d/Y');

        if ($request->filled('booking_date')) {

            $date       = explode('-', $request->booking_date);
            $check_in   = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $check_out  = Carbon::parse(trim($date[1]))->format('Y-m-d');

        }

        $this->removeAllCookieItems();

        $data['categories'] = (new RoomStatusService())->availableRoom($check_in, $check_out);


        return view('booking/booking_ui', $data);
    }





    /*
     |--------------------------------------------------------------------------
     | bookingUi METHOD FOR Box Style Room Booking
     |--------------------------------------------------------------------------
    */
    public function HouseKeeping(Request $request)
    {
        $this->hasAccess("Booking.HouseKeeping");

        $check_in                   = date('Y-m-d');
        $check_out                  = date('Y-m-d', strtotime($check_in . "+1 days"));

        $data['mix_date']           = $check_in . ' - ' . $check_out;
        $data['booking_date']       = date('m/d/Y') . ' - ' . Carbon::now()->addDay()->format('m/d/Y');

        if ($request->filled('booking_date')) {

            $date       = explode('-', $request->booking_date);
            $check_in   = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $check_out  = Carbon::parse(trim($date[1]))->format('Y-m-d');

        }

        $this->removeAllCookieItems();

        $data['categories'] = (new RoomStatusService())->availableRoom($check_in, $check_out);


        return view('house-keeping/index', $data);
    }








    /*
     |--------------------------------------------------------------------------
     | nextStep METHOD FOR Box Style Room Booking Next Step
     |--------------------------------------------------------------------------
    */
    public function nextStep(Request $request)
    {
        // dd(Cookie::get('booking_info'));

        try {
            $booking_data = [];

        if (Cookie::get('booking_info')) {

            $booking_info = stripslashes(Cookie::get('booking_info'));
            $booking_data = json_decode($booking_info, true);
        }

        $data['request_date']   = $request->only('booking_availabe');
        $data['booking']        = $booking_data;
        $data['categories']     = RoomCategory::with('roomPrices:id,room_category_id,price,capacity')->get();
        $data['countries']      = Country::pluck('name', 'id');
        $data['rooms']          = Rooms::roomNumber();
        $data['guest']          = Guest::where('status', 1)->get();
        $data['booking_purpose']= BookingPurpose::active()->get();
        $data['vat']            = Vat::first();
        $data['account_types']  = AccountType::where('status', 1)->pluck('name', 'id');

        $data['crmCompanies']   = CrmCustomer::where('org_name','!=', null)
        //  ->where('is_customer', 1)
        ->select('id','org_name','org_phone','org_email','address')
        ->get();
        // dd(Cookie::get('booking_info'));
        } catch (\Throwable $th) {
            throw $th;
        }

        return view('booking/booking_next', $data);
    }



    /*
     |--------------------------------------------------------------------------
     | available METHOD FOR Check availability for new room
     |--------------------------------------------------------------------------
    */
    /**
     * @deprecated since W3.8 (this method is not wired to any route in
     *             module/BanquetHall/routes/web_banquet_hall.php).
     *             The "check availability for new room" flow is no longer
     *             used; the available-room check is now done inline by
     *             RoomStatusService::availableRoom() at the controller
     *             entry points. Kept for now in case an external caller
     *             still hits it via routing-by-reflection. Remove in
     *             W3.9 if no caller emerges.
     */
    public function available(Request $request)
    {

        $req_date       = $request->booking_availabe;
        $date_split     = explode('-', $req_date);
        $check_in       = date('Y-m-d', strtotime($date_split[0]));
        $check_out      = date('Y-m-d', strtotime($date_split[1]));

        $categories = (new RoomStatusService())->availableRoom($check_in, $check_out);
        return view('booking.booking_ui', compact('categories'));
    }






    //-------------------------------------------------------------------------//
    //                      CHECK ROOM AVAILABILITY METHOD                     //
    //-------------------------------------------------------------------------//
    public function checkRoomAvailability(Request $request)
    {
        try {

            $check_in   = $request->check_in_date;
            $check_out  = $request->check_out_date;
            $room_id    = $request->room_id;
            $booking_id = $request->booking_id;

            $bookingDateDetails = BookingDateDetails::where('booking_id', '!=', $booking_id)->where('room_id', $room_id);

            $data['is_booked'] = $bookingDateDetails->where('date', '>=' ,$check_in)
                                                    ->where('date', '<=' ,$check_out)
                                                    ->whereIn('status', [1,2])
                                                    ->count();


            $data['is_reservation'] = $bookingDateDetails->where('date', '>=' ,$check_in)
                                                    ->where('date', '<=' ,$check_out)
                                                    ->where('status', 0)
                                                    ->count();

            return $data;

        } catch (\Throwable $th) {
            return $th;
        }

    }





    /*
     |--------------------------------------------------------------------------
     | ADD BOOKING METHOD FOR MAKE ROOM BOOKING
     |--------------------------------------------------------------------------
    */
    public function addBooking(Request $request)
    {

        try {

            if (Cookie::get('booking_info')) {

                $cookie_data = stripslashes(Cookie::get('booking_info'));
                $booking_data = json_decode($cookie_data, true);
            } else {

                $booking_data = array();
            }

            if (collect($booking_data)->where('room_id', $request->room_id)->count() > 0) {

                return response()->json([
                    'status'    => 1,
                    'data'      => 'Your request have been stored',
                    'message'   => 'Success',
                ]);
            }
            // Calculate Days
            $get_date   = $request->date;
            $split_date = explode('-', $get_date);
            $check_in   = date('Y-m-d', strtotime($split_date[0]));
            $check_out  = date('Y-m-d', strtotime($split_date[1]));

            // After Doing Night Audit Generate, Wouldn't Generate Booking Today From Dashboard.

            // if (NightAuditSummary::query()->where('date', $check_in)->count() > 0) {
            //     return response()->json([
            //         'status'        => 0,
            //         'data'          => 'Unable to booked.',
            //         'message'       => 'Error',
            //     ]);

            // }

            // calculate nights
            $start_date  = strtotime($check_in);
            $end_date    = strtotime($check_out);
            $datediff    = $end_date - $start_date;
            $night_count = round($datediff / (60 * 60 * 24));

            $room     = Rooms::where('id', $request->room_id)->first();
            $category = RoomCategory::where('id', $request->category_id)->first();


            if ($room) {

                $item_array = array(
                    'category_id'    => $category->id,
                    'room_id'        => $room->id,
                    'room_number'    => $room->room_number,
                    'category_name'  => $category->name,
                    'category_price' => $category,
                    'check_in'       => $check_in,
                    'check_out'      => $check_out,
                    'nights'         => $night_count
                );

                $booking_data[]   = $item_array;
                $added_item       = $item_array;
                $booking_store    = \json_encode($booking_data);
                $minutes          = 1000;
                Cookie::queue(Cookie::make('booking_info', $booking_store, $minutes));

                if (Cookie::get('booking_info')) {

                    $cookie_data       = \stripslashes(Cookie::get('booking_info'));
                    $booking_data      = \json_decode($cookie_data, true);
                    $booking_count     = count($booking_data);
                    $booking_count++;
                } else {

                    $booking_count = 0;
                }


                return response()->json([
                    'status'    => 1,
                    'data'      => 'Successfully added.',
                    'message'   => 'Success',
                    'booking_count' => $booking_count
                ]);
            }
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }




    /*
     |--------------------------------------------------------------------------
     | REMOVE BOOKING METHOD FOR REMOVE ROOM BOOKING
     |--------------------------------------------------------------------------
    */
    public function removeNextBk(Request $request)
    {
        $prod_id = $request->input('product_id');

        return $this->removeCookieItem($prod_id);
    }



  /*
    |------------------------------------------------------------------------------------------------------------
    | REMOVE ALL COOKIE ITEM
    |------------------------------------------------------------------------------------------------------------
    */
    private function removeAllCookieItems()
    {
        if (isset($_COOKIE['booking_info'])) {
            unset($_COOKIE['booking_info']);
            setcookie('booking_info', '', time() - 3600, '/'); // empty value and old timestamp
        }
    }





    /*
    |------------------------------------------------------------------------------------------------------------
    | REMOVE COOKIE ITEM
    |------------------------------------------------------------------------------------------------------------
    */

    public function removeCookieItem($prod_id, $is_response = true)
    {
        $cookie_data        = stripslashes(Cookie::get('booking_info'));
        $booking_data       = json_decode($cookie_data, true);
        $item_id_list       = array_column($booking_data, 'room_id');

        $prod_id_is_there   = $prod_id;


        if (in_array($prod_id_is_there, $item_id_list)) {
            foreach ($booking_data as $keys => $values) {
                if ($booking_data[$keys]["room_id"] == $prod_id) {
                    unset($booking_data[$keys]);
                    $item_data = json_encode($booking_data);
                    $minutes = 50;

                    Cookie::queue(Cookie::make('booking_info', $item_data, $minutes));

                    if (Cookie::get('booking_info')) {
                        $cookie_data = \stripslashes(Cookie::get('booking_info'));
                        $booking_data   = \json_decode($cookie_data, true);
                        $cart_count  = count($booking_data);
                        $cart_count--;
                    } else {
                    }
                    if($is_response){
                        return response()->json([
                            'status'        => 1,
                            'data'          => 'Successfully Removed!',
                            'message'       => 'Success',
                            'cart_count'    => $cart_count
                        ]);
                    }
                }
            }
        }
    }





    /*
    |------------------------------------------------------------------------------------------------------------
    | CANCEL BOOKING IF NOT ARRIVED
    |------------------------------------------------------------------------------------------------------------
    */
    public function cancelBooking($id)
    {
        try {

            $this->service->CancelBooking($id);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('message', 'Booking Cancelled Successfully !');
    }



    //---------------------------------------------------------//
    //          DELETE ALL BOOKING SCRIPTING QUERY             //
    //---------------------------------------------------------//
    public function deleteAllBooking()
    {
        DB::transaction(function () {

            $bookings = Booking::get();


            foreach ($bookings as $booking) {

                $guest = $booking->guestInfo;

                if ($guest) {
                    $guest->update([
                        'booking_id' => null,
                    ]);
                }

                $booking->transection->delete();

                $booking->hotel_transaction()->delete();
                $booking->transection_ledgers()->delete();
                $booking->bookingDates()->delete();
                $booking->bookingAdjusts()->delete();
                $booking->bookingDetails()->delete();
                $booking->transactions()->delete();
                $booking->bookingExtraCharge()->delete();
                $booking->members()->delete();
                $booking->delete();
            }

        });

        return redirect()->route('home')->with('message', 'Booking Deleted Successfully !');
    }






    //---------------------------------------------------------//
    //              EXTEND CHECKOUT DATE METHOD                //
    //---------------------------------------------------------//
    public function extendCheckoutDate()
    {
        DB::transaction(function () {

            return 'working';

        });
    }


    //---------------------------------------------------------//
    //             BOOKING DUE COLLECTION METHOD               //
    //---------------------------------------------------------//
    public function BookingCollection(Request $request)
    {
        $this->hasAccess("hotel.booking-collection");

        $data['hotelGuests']    =   Guest::where('status', 1)->get();
        $data['customers']      =   CRMCustomer::orderByDesc('id')->get();
        $data['account_type']   = AccountType::where('status', 1)->pluck('name', 'id');

        $data['transactions']   = 0;
        $data['hotelGuest']     = '';

        if($request->hotel_guest_id){

        if (request()->has('hotel_guest_id')) {

            $data['hotelGuest']     = Guest::where('id', $request->hotel_guest_id)->first();

            $data['transactions']   =   HotelTransection::where('source_type', 'Booking')
                                                        ->where('due_amount', '>', 0)
                                                        ->whereHas('HotelSale', function($q) use($request){
                                                            $q->where('customer_id', $request->hotel_guest_id);
                                                        })
                                                        ->with('source')
                                                        ->get();
        }
        }

        if($request->company_id){
        if (request()->has('company_id')) {

            $data['hotelGuest']       = Booking::where('company_id', $request->company_id)->first();

            // $data['hotelCompany']     =  CRMCustomer::where('id', $request->company_id)->first();

            $data['transactions']     =   HotelTransection::where('source_type', 'Booking')
                                                        ->where('due_amount', '>', 0)
                                                        ->whereHas('HotelSale', function($q) use($request){
                                                            // $q->where('customer_id', $request->company_id);
                                                            $q->where('company_id', $request->company_id);
                                                        })
                                                        ->with('source')
                                                        ->get();
        }
    }

        return view('payment-collection.index', $data);
    }





    //---------------------------------------------------------//
    //       BOOKING DUE COLLECTION STORE METHOD               //
    //---------------------------------------------------------//
    public function StoreCollect(Request $request)
    {
        $this->hasAccess("hotel.booking-collection-store");

        try {

            $this->service->collectDue($request);

            return redirect()->route('booking-collection')->with('message', 'Payment Collected Successfully!');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

    }


}
