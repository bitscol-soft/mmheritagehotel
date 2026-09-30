<?php


use Illuminate\Support\Facades\Route;
use Module\Hotel\Controllers\GuestController;
use Module\Hotel\Controllers\RoomsController;
use Module\Hotel\Controllers\BookingController;
use Module\Hotel\Controllers\Ajax\AjaxController;



Route::get('room_by_category/{id}',             [BookingController::class, 'getRooms']);
Route::get('room_for_booking/{id}',             [BookingController::class, 'getRoomsForBooking']);
Route::get('room_by_search_category/{id}',      [BookingController::class, 'roomSearchCategory']);

Route::post('add_booking',                      [BookingController::class, 'addBooking']);
Route::post('remove_booking',                   [BookingController::class, 'removeBooking']);
Route::match(['get', 'post'], 'remove_booking_next', [BookingController::class, 'removeNextBk']);

Route::get('get-sms-balance',                   [GuestController::class, 'getSMSBalance']);




/*
|-------------------------------------------------------------------------------------------------
| BOOKING SERVICE
|-------------------------------------------------------------------------------------------------
*/

Route::get('get-booking-details',               [AjaxController::class, 'getBookingDetails'])->name('get-booking-details');
Route::get('get-booking-member-details',        [AjaxController::class, 'getBookingMemberDetail'])->name('get-booking-member-details');


Route::get('check-available-room',              [AjaxController::class, 'checkRoomForBooking'])->name('check-available-room');
// Route::get('check-available-room',              [AjaxController::class, 'getAvailableRoom'])->name('check-available-room');
Route::get('get-available-room',                [AjaxController::class, 'getAvailableRoom'])->name('get-available-room');
Route::post('cancel-booking/{id}',              [BookingController::class, 'cancelBooking'])->name('cancel-booking');
Route::get('booking-check-by-date',             [AjaxController::class, 'roomCheck']);
Route::post('booking-cart',                     [AjaxController::class, 'bookingCartStore']);

Route::get('get-tables',                        [AjaxController::class, 'getTable']);

Route::post('update-room-status/{id}',          [RoomsController::class, 'updateStatus'])->name('update-room-status');

Route::post('update-room-status-keeping/{id}',          [RoomsController::class, 'updateKeepingStatus'])->name('update-room-status-keeping');


Route::get('check-night-audit',                 [AjaxController::class, 'checkNightAudit'])->name('check-night-audit');

Route::get('get-guest-data',                    [AjaxController::class, 'GetGuestData'])->name('get-guest-data');






/**
 * --------------------------------------------------------------------------------------------------
 *  AJAX ROUTE
 * --------------------------------------------------------------------------------------------------
 */
Route::get('inhouse-guest-information',         [AjaxController::class, 'inHouseGuestInformation'])->name('inhouse-guest-information');

Route::get('inhotel-guest-information',         [AjaxController::class, 'HotelGuestInformation'])->name('inhotel-guest-information');

Route::get('get-crm-company',                   [AjaxController::class, 'GetCrmCompany'])->name('get-crm-company');
