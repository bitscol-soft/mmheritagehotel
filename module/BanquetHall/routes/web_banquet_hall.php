<?php


use Illuminate\Support\Facades\Route;
use Module\BanquetHall\Controllers\AjaxController;
use Module\BanquetHall\Controllers\BanquetRoomController;
use Module\BanquetHall\Controllers\BanquetBookingController;
use Module\BanquetHall\Controllers\BanquetAmenitieController;
use Module\BanquetHall\Controllers\BanquetCategoryController;
use Module\BanquetHall\Controllers\BanquetBookingPurposeController;

Route::group(['prefix' => 'BanquetHall', 'as' => 'banquet.'], function () {

    /*
    |--------------------------------------------------------------------------
    | ROOM MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    */

       Route::group(['prefix' => 'room-management'], function () {
        Route::resources([
            'hall-categories'       => BanquetCategoryController::class,
            'hall-rooms'            => BanquetRoomController::class,
            'aminities'             => BanquetAmenitieController::class,
        ]);
    });
/*
    |--------------------------------------------------------------------------
    | INVOICE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('invoice/{id}',             [BanquetBookingController::class, 'getInvoice'])->name('generate.invoice');
    Route::get('invoice-v2/{id}',          [BanquetBookingController::class, 'getInvoiceV2'])->name('generate.invoice-v2');
    Route::get('reservation-invoice/{id}', [BanquetBookingController::class, 'reservationInvoice'])->name('generate.reservation-invoice');


    /*
    |--------------------------------------------------------------------------
    | BOOKING MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    */

       Route::group(['prefix' => 'booking'], function () {
        Route::resources([
            'booking-purpose'       => BanquetBookingPurposeController::class,
            'booking'               => BanquetBookingController::class,
        ]);
    });





    // // ---------------------------------------------------------------//
    // //                        CUSTOM ROUTE                            //
    // // ---------------------------------------------------------------//
    Route::post('booking-checkin/{id}',             [BanquetBookingController::class, 'getCheckIn'])->name('check.in.update');

    Route::post('due-collection/{id}',              [BanquetBookingController::class, 'dueCollection'])->name('due-collection');


    // // ---------------------------------------------------------------//
    // //                       AJAX ROUTE                               //
    // // ---------------------------------------------------------------//
    // Route::get('room_for_booking',             [BanquetBookingController::class, 'getRoomsForBooking']);
    // Route::get('room_for_booking/{id}',             [BanquetBookingController::class, 'roomSearchCategory']);



});
