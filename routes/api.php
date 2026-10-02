<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ApiDashboardController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login',       [LoginController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']],function () {


    Route::post('logout',       [LoginController::class, 'logout']);

});
Route::group(['middleware' => ['api.token.verify']], function () {

    Route::post('dashboard', [ApiDashboardController::class, 'index']);


    Route::post('all-users', [ApiDashboardController::class, 'allUserList']);


});



Route::match(['get', 'post'], 'push', [LogController::class, 'push']);







