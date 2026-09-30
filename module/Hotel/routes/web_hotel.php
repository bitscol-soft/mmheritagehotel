<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Module\Hotel\Models\HotelTransection;
use Module\Hotel\Controllers\vatController;
use Module\Hotel\Controllers\GuestController;
use Module\Hotel\Controllers\RoomsController;
use Module\Hotel\Controllers\BookingController;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Hotel\Controllers\AminitiesController;
use Module\Hotel\Controllers\AccountTypeController;
use Module\Hotel\Controllers\BookingNoteController;
use Module\Hotel\Controllers\GuestUploadController;
use Module\Hotel\Controllers\RoomCategoryController;
use Module\Hotel\Controllers\BookingAdjustController;
use Module\Hotel\Controllers\Report\ReportController;
use Module\Hotel\Controllers\BookingPurposeController;
use Module\Hotel\Controllers\Report\CashFlowController;
use Module\Hotel\Controllers\NightAuditSummaryController;
use Module\Hotel\Controllers\CurrencyConversionController;
use Module\Hotel\Controllers\GuestRegistrationTermsController;


Route::group(['prefix' => 'hotel'], function () {


    Route::group(['prefix' => 'room-management'], function () {
        Route::resources([
            'hotel-categories'  => RoomCategoryController::class,
            'aminities'         => AminitiesController::class,
            'rooms'             => RoomsController::class,
        ]);
        // vatController only implements index/update (inline form on the index page)
        Route::resource('vat', vatController::class)->only(['index', 'update']);
        // AccountTypeController has no create/show views
        Route::resource('account-type', AccountTypeController::class)->except(['create', 'show']);
    });



    Route::get('booking-ui',                        [BookingController::class, 'bookingUi'])->name('booking.ui');
    Route::get('check-avaiable',                    [BookingController::class, 'available'])->name('booking.available');
    Route::get('booking/next-step',                 [BookingController::class, 'nextStep'])->name('booking.next.step');



    Route::get('house-keeping',                     [BookingController::class, 'HouseKeeping'])->name('Booking.HouseKeeping');

    // Booking Room Assign
    Route::put('booking-assign/{id}',               [BookingController::class, 'assign'])->name('booking.assign');



    // static booking/* routes must be registered BEFORE the booking resource,
    // otherwise booking/{booking} (show) swallows e.g. /booking/booking-adjusts -> find('booking-adjusts') -> 500
    Route::group(['prefix' => 'booking'], function () {


        Route::resource('booking-adjusts',     BookingAdjustController::class);

        Route::get('invoice/{id}',             [BookingController::class, 'getInvoice'])->name('generate.invoice');
        Route::get('invoice-v2/{id}',          [BookingController::class, 'getInvoiceV2'])->name('generate.invoice-v2');
        Route::get('reservation-invoice/{id}', [BookingController::class, 'reservationInvoice'])->name('generate.reservation-invoice');
        Route::get('rest-sale-invoice/{id}',   [BookingController::class, 'restSaleInvoice'])->name('generate.rest-sale-invoice');
    });


    //--------------------- RESOURCES ---------------------//
    Route::resources([
        'guests'                    => GuestController::class,
        'guest-uploads'             => GuestUploadController::class,
        'booking'                   => BookingController::class,
        'booking-purpose'           => BookingPurposeController::class,
        'booking-note'              => BookingNoteController::class,
        'guest-registration-terms'  => GuestRegistrationTermsController::class,
        'night-audits'              => NightAuditSummaryController::class,
        'currency-conversions'      => CurrencyConversionController::class,

    ]);

    //--------------- CUSTOM ROUTE FOR REFERRED BOOKING ---------------//
    Route::get('referred-booking',                  [BookingController::class, 'index'])->name('booking.referred-booking');
    Route::post('/update-status/{table}',           [Controller::class, 'updateStatus'])->name('update-status');

    // Route::post('/update-status-keeping/{table}',            [Controller::class, 'updateKeepingStatus'])->name('update-status-keeping');


    Route::post('guest-image-update',            [GuestController::class, 'guestImageUpdate'])->name('guest-image-update');








    //-------------------------------------------------------//
    //                      CUSTOM ROUTE                     //
    //-------------------------------------------------------//
    Route::get('booking-search-by-date',            [BookingController::class, 'getSearch'])->name('search.by.date');
    Route::post('booking-checkin/{id}',             [BookingController::class, 'getCheckIn'])->name('check.in.update');
    Route::post('booking-checkout/{id}',            [BookingController::class, 'getCheckout'])->name('booking.checkout');

    // Route::post('booking-due-collection/{id}',      [BookingController::class, 'dueCollection'])->name('booking.due-collection');
    Route::post('booking-due-collection',           [BookingController::class, 'dueCollectionMulti'])->name('booking.due-collection');
    Route::get('booking-collection',                [BookingController::class, 'BookingCollection'])->name('booking-collection');
    Route::post('store-collection',                 [BookingController::class, 'StoreCollect'])->name('store-payment-collection');
    Route::post('booking-extra-charge',             [BookingController::class, 'extraCharge'])->name('booking.extra-charge');
    Route::get('delete-all-booking-by-query',       [BookingController::class, 'deleteAllBooking'])->middleware('super-admin')->name('delete-all-booking-by-query');
    Route::get('check-room-availability',           [BookingController::class, 'checkRoomAvailability'])->name('check-room-availability');
    Route::post('extend-checkout-date/{id}',        [BookingController::class, 'extendCheckoutDate'])->name('booking.extend-checkout-date');


    //Bar Payment Get In Booking
    // Route::get('extend-checkout-date',        [BookingController::class, 'extendCheckoutDate'])->name('booking.extend-checkout-date');

    // ROOM NUMBER CHEKCING [CATEOGRY WISE] CUSTOM ROUTE
    Route::get('rooms-check-room-number',           [RoomsController::class, 'checkRoomNumber'])->name('rooms.check-room-number');
    Route::get('get-hotel-guest-info',              [GuestController::class, 'getHotelGuestInfo'])->name('rooms.get-hotel-guest-info');
    Route::post('update-hotel-guest-info',          [GuestController::class, 'updateHotelGuestInfo'])->name('rooms.update-hotel-guest-info');
    Route::get('get-customer-info',                 [GuestController::class, 'getCustomerInfo'])->name('get-customer-info');
    Route::get('get-guest-info',                    [GuestController::class, 'getGuestInfo'])->name('get-guest-info');
    Route::get('guest-info-invoice/{id}',           [GuestController::class, 'guestInfoInvoice'])->name('guests.invoice');





    //-------------------------------------------------------//
    //                  SMS CUSTOM ROUTE                     //
    //-------------------------------------------------------//
    Route::get('guests/send/sms',                   [GuestController::class, 'sendSms'])->name('guests.send-sms');
    Route::post('guests/submit/sms',                [GuestController::class, 'submitSms'])->name('guests.submit-sms');



    require_once __DIR__ . '/web_hotel_ajax.php';



    //---------------------------------------------------------------//
    //                          REPORT ROUTE                         //
    //---------------------------------------------------------------//
    Route::group(['prefix' => 'reports', 'as' => 'report.'], function () {

        Route::get('monthly-summaries',                 [ReportController::class, 'monthlyBookingReport'])->name('monthly');

        Route::get('monthly-booking-summaries',         [ReportController::class, 'monthlyBookingSummaryReport'])->name('monthly-booking');

        Route::get('cash-flows',                        [CashFlowController::class, 'index'])->name('cash-flows');

        Route::get('night-audits',                      [ReportController::class, 'nightAudit'])->name('night-audit');

        Route::get('night-audits-detailsShow/{id}',     [ReportController::class, 'detailsShow'])->name('detailsShow');

        Route::get('room-logs',                         [ReportController::class, 'roomLog'])->name('room-log');

        Route::get('services',                          [ReportController::class, 'service'])->name('service');

        Route::get('today-activities',                  [ReportController::class, 'todayActivity'])->name('today-activities');

        Route::get('expected-arrival',                  [ReportController::class, 'expectedArrival'])->name('expected-arrival');

        Route::get('expected-departure',                [ReportController::class, 'expectedDeparture'])->name('expected-departure');

        Route::get('in-house-guest',                    [ReportController::class, 'inHouseGuest'])->name('in-house-guest');

        Route::get('report-over-all',                   [ReportController::class, 'ReportOverAll'])->name('report.over-all');

        Route::get('daily-check-in',                    [ReportController::class, 'DailyCheckIn'])->name('daily-check-in');

        Route::get('daily-check-out',                   [ReportController::class, 'DailyCheckOut'])->name('daily-check-out');

        Route::get('today-in-house',                    [ReportController::class, 'TodayHouse'])->name('today-in-house');

        Route::get('dailyVat',                          [ReportController::class, 'vatDaily'])->name('vatDaily');

        Route::get('monthlyVat',                        [ReportController::class, 'vatMonthly'])->name('vatMonthly');



    });


    Route::get('update-transaction-invoice', function(){

        HotelTransection::with('source')->has('source')->whereIn('source_type', ['Bar Sale', 'Resturent Sale', 'Restaurant Sale'])->get()->map(function($item){

            $item->update([
                'invoice_no'    => optional($item->source)->invoice_no ?? $item->invoice_no,
            ]);

        });

        return 'transaction invoice no update success';
    });


    Route::get('update-transaction-ledger-time', function(){

        HotelTransactionLedger::query()->whereNull('datetime')->get()->map(function($item){

            $item->update([
                'datetime'    => fdate($item->created_at,'Y-m-d H:i:s'),
            ]);

        });

        return 'transaction invoice no update success';
    });

    Route::get('night-audit-transaction',           [NightAuditSummaryController::class, 'nightAuditTransaction']);

    Route::get('night-audit-transaction-update',    [NightAuditSummaryController::class, 'nightAuditTransactionsDelete']);

});
