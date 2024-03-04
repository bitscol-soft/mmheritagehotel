<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use App\Traits\SendNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Mail\ReplyContactMail as MailReplyContactMail;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Module\Hotel\Models\HotelTransection;
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

            // get  the last inserted booking id
            $booking_id = $this->service->booking->id;
            
            $data['transactions'] = HotelTransection::with('source','transaction_ledgers')->where('source_type', 'Booking')
                                ->where('booking_id', $booking_id)->get();

            $data['booking']      = Booking::with(['bookingDetails' => function($q){
                                    $q->with('roomNumber')->with('roomCategory');
                                }])->with('getVat', 'bookingExtraCharge','payBy')->with('paymentType')->find($booking_id);

            $mailData = [
                'company_name' => $data['company']->name,
                'company_headoffice' => $data['company']->head_office,
                'company_mobile' => $data['company']->phone_number,
                'company_email' => $data['company']->email,
                'name' => $guest->name,
                'mobile' => $guest->phone_no,
                'email' => $guest->email,
                'address' => $guest->address,
                'room_name' => $data['booking']->bookingDetail->roomCategory->name,
                'room_no' => $data['booking']->bookingDetail->roomNumber->room_number,
                'booking_no' => $data['booking']->booking_number,
                'booking_date' => $data['booking']->booking_date,
                'check_in_date' => $data['booking']->check_in_date,
                'check_out_date' => $data['booking']->check_out_date,
            ];
            $replymailData = [
                'company_name' => $data['company']->name,
                'company_headoffice' => $data['company']->head_office,
                'company_mobile' => $data['company']->phone_number,
                'company_email' => $data['company']->email,
                'name' => $guest->name,
                'mobile' => $guest->phone_no,
                'email' => $guest->email,
                'address' => $guest->address,
                'room_name' => $data['booking']->bookingDetail->roomCategory->name,
                'room_no' => $data['booking']->bookingDetail->roomNumber->room_number,
                'booking_no' => $data['booking']->booking_number,
                'booking_date' => $data['booking']->booking_date,
                'check_in_date' => $data['booking']->check_in_date,
                'check_out_date' => $data['booking']->check_out_date,
                'content' => 'Thank you for choosing  our Hotel for your upcoming visit. Our team is dedicated to ensuring your stay is both comfortable and memorable. For any queries or special requests, please do not hesitate to contact us directly.'
            ];
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactMail($mailData));
            Mail::to($guest->email)->send(new MailReplyContactMail($replymailData));

            $data['company'] = Company::first();
            return view('frontend.booking-success', ['guest' => $guest, 'data' => $data])->with('bookingSuccessMessage', 'Your booking have been successfully Reserved!');
            // return redirect()->route('home.page')->with('bookingSuccessMessage', 'Your booking have been successfully Reserved!');

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
