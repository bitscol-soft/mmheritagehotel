<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Services\FrontendBookingService;

class GuestController extends Controller
{

    use SendNotification;

    private $service;



    //--------------------------------------------------------------------------//
    //                          CONSTRUCT METHOD                                //
    //--------------------------------------------------------------------------//
    public function __construct()
    {
        $this->service          = new FrontendBookingService();
    }



    //--------------------------------------------------------------------------//
    //                      GUEST REGISTRATION METHOD                           //
    //--------------------------------------------------------------------------//
    public function guestRegistration(Request $request)
    {
        // return $request->all();

        if ($request->check_in > $request->check_out) {
            return redirect()->back()->with('error', 'Check out date can not be less than check in date!');
        }

        $data['request']        = $request;

        return view('frontend.guest-register', $data);
    }



    //--------------------------------------------------------------------------//
    //                      GUEST REGISTRATION METHOD                           //
    //--------------------------------------------------------------------------//
    public function submitGuestRegistration(Request $request)
    {
        // return $request;

        $request->validate([
            'room_category' => 'required',
            'room_id'       => 'required',
            'check_in'      => 'required',
            'check_out'     => 'required',
        ]);

        $guest = Guest::where('phone_no', $request->phone_no)->select(['id','name','email','phone_no','booking_id'])->first();

        if ($guest != null && $guest->booking_id != null) {
            return redirect()->back()->with('alreadyBookingError', 'You already have a booking by this number!');
        }

        $data['request']        = $request;

        return view('frontend.booking-register', $data);
    }



    //--------------------------------------------------------------------------//
    //                   SUBMIT BOOKING REGISTRATION METHOD                     //
    //--------------------------------------------------------------------------//
    public function submitBookingRegistration(Request $request)
    {
        // return $request->all();

        // try {

            $guest = Guest::where('phone_no', $request->phone_no)->select(['id','name','email','phone_no','booking_id'])->first();

            if ( $guest == null) {

                $guest = Guest::create([
                    'name'                  => $request->name,
                    'email'                 => $request->email,
                    'phone_no'              => $request->phone_no,
                    'address'               => $request->address,
                    'nid_no'                => $request->nid_no,
                    'spouse_name'           => $request->spouse_name,
                    'created_by'            => 1,
                ]);

            }
            

            DB::transaction(function () use ($request, $guest) {

                $difference         = strtotime($request->check_in) - strtotime($request->check_out);
                $nighCount          = abs($difference / 86400);

                $roomCategory       = RoomCategory::find($request->room_category);
                $roomPrice          = $roomCategory->price * $nighCount;

                $vat                = vatSetting()->hotel_vat;
                $service_percent    = vatSetting()->room_service_charge;

                $service_amount     = ($roomPrice / 100) * $service_percent;
                $vat_amount         = (($roomPrice + $service_amount) / 100) * $vat;


                //------------- BOOKING STORE -------------//
                $this->service->store($request, $guest, $vat_amount, $service_amount, $roomPrice);


                //--------- SET BOOKING INVOICE NO ---------//
                $this->service->setNextInvoiceNo('Booking', date('Y-m'));



                //---------- SAVE BOOKING DETAILS ----------//
                $this->service->saveBookingDetails($request, $this->service->booking->id, $nighCount, $vat_amount, $service_amount, $roomPrice);
            // dd($request->all(), $guest);



                //--------- UPDATE GUEST BOOKING ID ---------//
                $guest->update([ 'booking_id' => $this->service->booking->id ]);



                //------------ SEND SMS TO GUEST ------------//
                if (isset($request->sms)) {
                    $messages = 'Your booking have been successfully Reserved. Booking No. '.$this->service->booking->booking_number;
                    $this->sendSmsNotification($messages , $guest->phone_no);
                }


                //----------- SEND EMAIL TO GUEST -----------//
                if (isset($request->email)) {
                    $this->sendEmailNotification($this->service->booking->id, $guest->email);
                }


                //----------- SAVE MEMBER DETAILS -----------//
                $this->service->saveMemberDetails($request);



                //-------- REMOVE ALL BOOKING COOKIES --------//
                $this->removeAllCookieItems();

            });

            return redirect()->route('home.page')->with('bookingSuccessMessage', 'Your booking have been successfully Reserved!');

        // } catch (\Throwable $e) {
        //     return redirect()->back()->with('error', $e->getMessage());
        // }

    }
    
    private function removeAllCookieItems()
    {
        if (isset($_COOKIE['booking_info'])) {
            unset($_COOKIE['booking_info']);
            setcookie('booking_info', '', time() - 3600, '/'); // empty value and old timestamp
        }
    }




}
