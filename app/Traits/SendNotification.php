<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

trait SendNotification
{

    public function sendSmsNotification($message, $phone)
    {
       return  Http::get(env('SMS_OTP_BASE_URL'), [
            "apikey"             => env('SMS_OTP_API_KEY'),
            "secretkey"          => env('SMS_OTP_SECRET_KEY'),
            "callerID"           => env('SMS_OTP_CALLER_ID'),
            "toUser"             => '88'.$phone,
            "messageContent"     => $message,
        ]);
    }


    public function sendMultipleSmsNotification($message, $phone)
    {
        Http::get(env('SMS_OTP_BASE_URL'), [
            "apikey"             => env('SMS_OTP_API_KEY'),
            "secretkey"          => env('SMS_OTP_SECRET_KEY'),
            "callerID"           => env('SMS_OTP_CALLER_ID'),
            "toUser"             => implode(',', $phone->toArray()),
            "messageContent"     => $message,
        ]);
    }


    public function sendMultipleSmsToGuest($message, $phone)
    {
        Http::get(env('SMS_OTP_BASE_URL'), [
            "apikey"             => env('SMS_OTP_API_KEY'),
            "secretkey"          => env('SMS_OTP_SECRET_KEY'),
            "callerID"           => env('SMS_OTP_CALLER_ID'),
            "toUser"             => $phone,
            "messageContent"     => $message,
        ]);
    }


    public function sendEmailNotification($bookingId, $guestEmail)
    {
        Mail::send('mails.booking-alert', ['bookingId' => $bookingId], function($message) use($guestEmail){
            $message->to($guestEmail);
            $message->subject('Booking Reservation');
        });
    }


}
