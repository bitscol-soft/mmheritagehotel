<?php
namespace Module\Hotel\Services;
use Carbon\CarbonPeriod;
use Module\Hotel\Models\Booking;
use Module\Hotel\Models\AccountType;
use Module\Hotel\Models\BookingAdjust;
use Module\Hotel\Models\BookingDateDetails;
use Module\Hotel\Models\BookingDetails;
use Module\HotelService\Services\HotelTransactionService;

class BookingAdjustService
{
    public $booking;

    public function store($request)
    {

        $this->booking = Booking::where('id', $request->booking_id)->first();

        $this->releasePreviousRoom();

        foreach (array_filter($request->room_ids ?? []) as $key => $room_id) {

            $booking_adjust = BookingAdjust::create([
                                'booking_id'        => $request->booking_id,
                                'to_date'           => $request->check_out_date,
                                'from_date'         => $request->check_out_date,
                                'from_room_id'      => $this->booking->bookingDetails[$key]->room_id,
                                'to_room_id'        => $request->previous_room_ids[$key],
                                'total_amount'      => $request->amounts[$key],
                                'discount'          => $request->discounts[$key] ?? 0,
                                'is_half_day'       => $request->is_half_day[$key] ?? 0,
                                'is_transfer'       => $request->is_transfer ?? 0,
                                'remarks'           => $request->is_transfer ? 'Transfer to another room' : 'Booking checkout date adjusted.',
                                'status'            => 1,
                            ]);

            $this->updateBooking($request);
            $this->updateBookingDetails($request, $key, $request->previous_room_ids[$key]);
            $this->bookingDates($booking_adjust, $request->check_out_date, $request->check_out_date);
            $this->transaction($request, $key, $booking_adjust);
        }
    }



    public function transaction($request, $key, $source)
    {

        $booking =  Booking::find($request->booking_id);

        (new HotelTransactionService())->storeTransaction(
            $source->id,
            'Booking', // 'Booking Adjust',
            $request->payment_type,
            ($request->advanced_amount + $request->due_amount), // total amount
            array_sum(array_filter($request->discounts)),
            ($booking->collection + $request->advanced_amount), //collection
            $request->vat_amount,
            $request->service_amount,
            $booking->id,
            $booking->booking_number,
            // today_from_system(),
            // $remark = null,
            // $extra_charge = 0,
            // $currency_type = 141
        );
    }


    public function updateBookingDetails($request, $key, $room_id)
    {
        $bookingDetails = BookingDetails::where([
            'booking_id'    => $request->booking_id,
            'room_id'       => $room_id
        ])->first();

        if ($bookingDetails) {
            // $calculate_total =
        } else {
            BookingDetails::create(
            [
                'booking_id'        => $request->booking_id,
                'room_id'           => $request->room_ids[$key],
                'guest_count'       => $request->guest_count[$key],
                'infant_count'      => $request->infant_count[$key],
                'night_count'       => $request->night[$key],
                'discount_amount'   => $request->discounts[$key],
                'service_charge'    => $request->service_charge[$key],
                'total_amount'      => $request->amounts[$key],
                'allow_breakfast'   => $request->allow_breakfast[$key],
            ]);
        }

        // BookingDetails::where('booking_id', $request->booking_id)->where('room_id', $room_id)->update([
        //     'guest_count'       => $request->guest_count[$key],
        //     'infant_count'      => $request->infant_count[$key],
        //     'night_count'       => $request->night[$key],
        //     'discount_amount'   => $request->discounts[$key],
        //     'service_charge'    => $request->service_charge[$key],
        //     'total_amount'      => $request->amounts[$key],
        //     'allow_breakfast'   => $request->allow_breakfast[$key],
        // ]);
    }


    public function updateBooking($request)
    {
        Booking::find($request->booking_id)->update([
            'check_out_date'    => $request->check_out_date,
        ]);
    }


    public function bookingDates($details, $from_date, $to_date)
    {
        try {
            $period = CarbonPeriod::create($from_date, $to_date);

            foreach ($period as $date) {

                BookingDateDetails::updateOrCreate([
                    'booking_id'            => $this->booking->id,
                    'room_id'               => $details->to_room_id,
                    'date'                  => fdate($date, 'Y-m-d'),
                ], [
                    'status'                => $this->booking->status
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    public function releasePreviousRoom()
    {
        BookingDateDetails::where([
            'booking_id'=> $this->booking->id,
            'date'      => $this->booking->check_in_date == request('migrate_date')
            ])->update([
            'status'    => 3
        ]);
    }

}
