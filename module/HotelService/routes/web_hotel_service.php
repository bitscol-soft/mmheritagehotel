<?php


use Illuminate\Support\Facades\Route;
use Module\HotelService\Controllers\AjaxController;
use Module\HotelService\Controllers\Services\HotelServiceController;
use Module\HotelService\Controllers\Services\HotelServiceSaleController;
use Module\HotelService\Controllers\HotelServiceNightAuditController;

Route::group(['prefix' => 'hotelservice', 'as' => 'hotelservice.'], function () {


    /*
    |--------------------------------------------------------------------------
    | MEDICAL SERVICE ROUTES
    |--------------------------------------------------------------------------
    */

    Route::resources([
        'services'                  => HotelServiceController::class,
        'service-sales'             => HotelServiceSaleController::class,
    ]);

    Route::put('service-due-receive/{id}',       [HotelServiceSaleController::class, 'dueReceive'])->name('service-due-receive');


    Route::get('get-guest-list',                [AjaxController::class, 'guestList'])->name('get-guest');
    Route::get('get-room-list',                 [AjaxController::class, 'roomList'])->name('get-rooms');
    Route::get('get-booking-number',            [AjaxController::class, 'bookingNoList'])->name('get-booking-number');
    Route::get('get-h-service',                 [AjaxController::class, 'getService'])->name('get-h-service');
    Route::get('guest-d-by-name/{id}',          [AjaxController::class, 'getDetailsByname'])->name('get-d-by-n');
    Route::get('guest-by-booking/{id}',         [AjaxController::class, 'getInfoByBooking'])->name('get-by-booking');
    Route::get('guest-by-rooms/{id}',           [AjaxController::class, 'getInfoByRoom'])->name('get-by-room');



    // ---------------------------------------------------------------//
    //               CUSTOM ROUTE FOR RESTOURANT NIGHT AUDIT          //
    // ---------------------------------------------------------------//
    Route::get('night-audit',              [HotelServiceNightAuditController::class, 'index'])->name('night-audits.index');
    Route::get('night-audit-show/{id}',    [HotelServiceNightAuditController::class, 'show'])->name('night-audits.show');



});
