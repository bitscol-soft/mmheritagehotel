<?php
namespace Module\Hotel\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\BookingAdjust;
use Module\Hotel\Models\BookingDetails;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Models\HotelTransection;
use Module\HotelService\Services\HotelTransactionService;

class BookingAdjustServiceV2
{
    public $booking;
    public $from_date;
    public $to_date;



    public function __construct() {

        $this->from_date = request('migrate_date');
        $this->to_date   = request('check_out_date');
    }





    public function updateBooking($request)
    {

        $this->booking = Booking::where('id', $request->booking_id)->first();
        $this->booking->update([
            'check_out_date'    => $request->check_out_date,
        ]);

        return $this->booking->refresh();
    }





    public function updateBookingDetail($request)
    {

        foreach (array_filter($request->room_ids) as $key => $room_id) {

            $detail = BookingDetails::where('booking_id', $request->booking_id)->where('room_id', $request->previous_room_ids[$key])->first();

            if ($detail) {
                $this->updateDetail($request, $detail);
                $this->deleteBookingDates($detail);
            }

            $detail = $this->addDetails($request, $key);

            $this->storeBookingAdjust($request, $key, $detail);

            $this->bookingDates($detail, $this->from_date, $this->to_date);
        }
    }


    public function storeBookingAdjust($request, $key, $detail)
    {
        BookingAdjust::create([
            'date'              => date('Y-m-d'),
            'booking_id'        => $request->booking_id,
            'booking_detail_id' => $detail->id,
            'to_date'           => $request->check_out_date,
            'from_date'         => $request->check_out_date,
            'from_room_id'      => $request->previous_room_ids[$key],
            'to_room_id'        => $detail->room_id,
            'total_amount'      => $request->amounts[$key] * $request->night[$key],
            'discount'          => $request->discounts[$key] ?? 0,
            'is_half_day'       => $request->is_half_day[$key] ?? 0,
            'is_transfer'       => $request->is_transfer ?? 0,
            'remarks'           => $request->is_transfer ? 'Transfer to another room' : 'Booking checkout date adjusted.',
            'status'            => 1,
        ]);

    }

    public function addDetails($request, $key)
    {
        // dd( $request->amounts[$key]);
        $room = Rooms::find($request->room_ids[$key]);
        $detail = BookingDetails::updateOrCreate([
                    'booking_id'        => $request->booking_id,
                    'room_id'           => $room->id,
                ],
                [
                    'current_room_rate' => $request->room_price[$key],
                    'category_id'       => $room->room_category,
                    'guest_count'       => $request->guest_count[$key],
                    'infant_count'      => $request->infant_count[$key],
                    'night_count'       => $request->night[$key],
                    'room_discount'     => $request->discounts[$key],
                    'discount_amount'   => $request->discounts[$key],
                    'service_charge'    => $request->service_charge[$key] ?? 0,
                    'total_amount'      => $request->amounts[$key] * $request->night[$key],
                    'allow_breakfast'   => $request->allow_breakfast[$key],
                    'status'            => $this->booking->status,
                ]);
                return $detail;

    }


    public function updateDetail($request, $detail)
    {

        $migrate_date            = request('migrate_date');
        $check_out_date          = request('check_out_date');
        $previous_check_out_date = $request->previous_check_out_date;


        if ($migrate_date == $previous_check_out_date) {

            $calculate_night = $detail->night_count;

        } else {

            $check_in_date   = Carbon::parse($migrate_date);
            $check_out_date  = Carbon::parse($previous_check_out_date);

            $differrence     = $check_out_date->diffInDays($check_in_date);

            $calculate_night = $detail->night_count - $differrence;

        }
        $detail->update([
            'night_count'   => $calculate_night,
            // 'total_amount'  => (($detail->total_amount + $detail->discount_amount) / $detail->night_count) * $calculate_night,
            'total_amount'  => ($detail->total_amount  / $detail->night_count) * $calculate_night,
        ]);



        //------------------- OLD CODE FOR CALCULATE NIGHT --------------------//
        // $calculate_night = $detail->night_count - $this->calculateNight();
        // if($calculate_night == 0){

        //     $detail->delete();

        // }else{
        //     $detail->update([
        //         'night_count'   => $calculate_night,
        //         'total_amount'  => (($detail->total_amount + $detail->discount_amount) / $detail->night_count) * $calculate_night,
        //     ]);
        // }


    }





    public function calculateNight()
    {
        $check_in_date = Carbon::parse(request('migrate_date'));
        $check_out_date = Carbon::parse(request('check_out_date'));
        return $check_out_date->diffInDays($check_in_date);
    }




    public function bookingDates($detail, $from_date, $to_date)
    {
        try {
            $period = CarbonPeriod::create($from_date, $to_date);

            foreach ($period as $date) {

                BookingDateDetails::updateOrCreate([
                    'booking_id'            => request('booking_id'),
                    'room_id'               => $detail->room_id,
                    'booking_detail_id'     => $detail->id,
                    'date'                  => fdate($date, 'Y-m-d'),
                ], [
                    'status'                => $this->booking->status
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function deleteBookingDates($detail)
    {
        try {
            $period = CarbonPeriod::create(request('migrate_date'), request('check_out_date'));

            foreach ($period as $date) {

                BookingDateDetails::where([
                    'booking_id'            => request('booking_id'),
                    'room_id'               => $detail->room_id,
                    'date'                  => fdate($date, 'Y-m-d'),
                ])->delete();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }





    public function transaction($request)
    {

        $booking =  Booking::with('details')->find($request->booking_id);


        $transaction = HotelTransection::where('source_type', 'Booking')->where('source_id', request('booking_id'))->first();

        (new HotelTransactionService())->storeTransaction(
            $booking,
            $request->booking_id,
            'Booking',
            $request->payment_type,
            // (convertToBDTCurrency($request->advanced_amount) + convertToBDTCurrency($request->due_amount)), // total amount
            $booking->details->sum('total_amount') + $booking->details->sum('service_charge') + $request->vat_amount,
            // array_sum(array_filter($request->discounts)),
            0,
            ($transaction->collection + convertToBDTCurrency($request->advanced_amount ?? 0)), //collection
            $request->vat_amount,
            $request->service_amount,
            $booking->id,
            $booking->booking_number,
            $booking->date,
            // $remark = null,
            // $extra_charge = 0,
            // $currency_type = 141
        );
    }

}
