<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SyncDataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Front\GuestController;
use App\Http\Controllers\SupplierTypeController;
use App\Http\Controllers\Front\BookingController;
use App\Http\Controllers\IdCardSettingController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\Front\HomePageController;
use App\Http\Controllers\SmartSoftPaymentScheduleController;

//====================================//
//          FRONTEND WEB ROUTES       //
//====================================//

// Route::group([], function () {

    Route::get('/',                 [HomePageController::class, 'homePage'])->name('home.page');

    Route::get('category',          [HomePageController::class, 'viewCategory'])->name('view.category');

    Route::get('category/{slug}',   [HomePageController::class, 'viewRoom'])->name('view.room');

    Route::get('terms-&-condition', [HomePageController::class, 'termsCondition'])->name('terms.condition');

    Route::get('privacy-&-policy',  [HomePageController::class, 'privacyPolicy'])->name('privacy.policy');

    Route::get('pages/{slug}',      [HomePageController::class, 'singlePage'])->name('pages.single');


    // Web Cart Booking
    Route::get('booking-cart',      [BookingController::class, 'bookingCart'])->name('booking.cart');


    // Ajax Routes
    Route::post('add-to-cart',      [BookingController::class, 'addToCart'])->name('add.to.cart');
    Route::get('remove-to-cart',    [BookingController::class, 'removeToCart'])->name('remove.to.cart');



    //-------------- GUEST REGISTRATION -------------//
    Route::get('guest/registration',           [GuestController::class, 'guestRegistration'])->name('guest-registration');
    Route::get('submit/guest/registration',    [GuestController::class, 'submitGuestRegistration'])->name('submit-guest-registration');

    Route::get('check/available/room',         [HomePageController::class, 'checkAvailableRoom'])->name('check-available-room');
    Route::post('submit/booking/registration', [GuestController::class, 'submitBookingRegistration'])->name('submit-booking-registration');




// });
//====================================//
//     END FRONTEND WEB ROUTES        //
//====================================//






Route::get('test', function(){ return 'success'; });
Route::post('push', [LogController::class,'push']);
Auth::routes();





//------------------------------------//
//        PASSWORD RESET ROUTES       //
//------------------------------------//
Route::group(['prefix' => 'password-reset','as' => 'password-reset.'], function () {
    Route::post('send-email',       [LoginController::class,'sendPasswordResetEmail'])->name('send-email');
    Route::get('verify-token',      [LoginController::class,'verifyResetPasswordToken'])->name('verify-token');
    Route::post('reset-password',   [LoginController::class,'updateUserPassword'])->name('update-password');
});





//------------------------------------------------------//
//            AUTH ROUTES FOR ADMIN [WEB-CMS]           //
//------------------------------------------------------//
Route::group(['middleware' => 'auth'], function () {

    Route::get('user/password/edit',    [UserController::class,'changePassword'])->name('user.password.edit');
    Route::post('user/password/edit',   [UserController::class,'updatePassword'])->name('user.password.update');

    // only access for super admin which id is 1

    Route::get('user/change/password/{id}', [UserController::class,'AdminChangePassword'])->name('admin.edit.password');
    Route::post('user/change/password',     [UserController::class,'AdminUpdatePassword'])->name('admin.update.password');

    Route::get('/home',                     [HomeController::class,'index'])->name('home');
    Route::get('/dashboard',                [HomeController::class,'dashboard'])->name('dashboard');

    // database backup
    Route::get('db-backup',                 [DatabaseBackupController::class,'db_backup'])->middleware('super-admin')->name('db-backup');
    Route::get('db-backup-to-drive',        [DatabaseBackupController::class,'databaseBackupToDrive'])->middleware('super-admin')->name('db-backup-to-drive');


    Route::resource('group',                GroupController::class);
    Route::get('/print-groups',             [GroupController::class,'printGroups'])->name('print.groups');

    Route::resource('company',              CompanyController::class);


    Route::resource('id-card-settings',     IdCardSettingController::class);


    Route::resource('system-setting',       SystemSettingController::class);


    Route::group(['prefix' => 'global-setting'], function () {

        Route::resource('suppliers',        SupplierController::class);
        Route::resource('supplier-types',   SupplierTypeController::class);
    });






    // Sync Data
    Route::get('sync-data',                 [SyncDataController::class,'syncData'])->name('sync-data');

    Route::get('sync-data-v2',              [SyncDataController::class,'syncDataV2'])->name('sync-data-v2');
    Route::get('sync-data-process',         [SyncDataController::class,'syncDataProcess'])->name('sync-data-process');

    Route::get('sync-attendance-fallback',  [SyncDataController::class,'syncAttendaceFallback'])->name('sync-attendance-fallback');

    Route::get('sync-monthly-summery',      [SyncDataController::class,'syncMonthlySummery'])->name('sync-monthly-summery');





    // end inventory module

    // smart soft payment
    Route::group(['middleware' => 'super-admin'], function () {

        Route::get('smart-soft-payments/alert', [SmartSoftPaymentScheduleController::class,'ajaxAlert'])->name('smart-soft-payments.alert');
        Route::resource('smart-soft-payments',  SmartSoftPaymentScheduleController::class);
    });

    Route::post('payment/feedback',             [SmartSoftPaymentScheduleController::class,'feedback'])->name('smart-soft-payments.feedback');


    Route::get('add-user',                      [UserController::class,'addUserFromEmployee']);


    Route::get('update-data', [SystemSettingController::class,'updateDataFromUrl']);
});

    Route::get('optimize-clear', function () {

        Artisan::call('optimize:clear');

        return redirect()->back();
    })->middleware(['auth', 'super-admin'])->name('optimize-clear');


    // debug on:
    Route::get('/update-debug', function () {

        if (config('app.debug') === false) {
            Artisan::call('debug on');
        } else {
            Artisan::call('debug off');
        }

        Artisan::call('optimize:clear');

        return redirect()->back()->with('message', 'DebugBar updated successfully');
    })->middleware(['auth', 'super-admin']);

