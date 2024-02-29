<?php

use Illuminate\Support\Facades\Route;
use Module\Restaurant\Controllers\Api\ApiController;
use Module\Restaurant\Controllers\Api\SaleController;
use Module\Restaurant\Controllers\Api\GuestController;
use Module\Restaurant\Controllers\Api\InventoryApiController;

Route::group(['prefix'  => 'api/v1', 'as' => 'api.', 'middleware'   => ['auth:sanctum']],function(){


    Route::apiResource('guests',                      GuestController::class);
    Route::get('account-types',                       [ApiController::class,          'accountType']);

    Route::get('get-guest-details/{guest_id}',        [GuestController::class,         'getGuestDetails']);

    Route::group(['prefix'  => 'inventory'],function(){

        Route::get('get-sale-invoice',                [InventoryApiController::class, 'getInvoice']);

        Route::get('tables',                          [InventoryApiController::class, 'table']);

        Route::get('products',                        [InventoryApiController::class, 'index']);

        Route::get('product-categories',              [InventoryApiController::class, 'productCategory']);

        Route::get('get-products',                    [InventoryApiController::class, 'getProduct']);
        Route::get('get-products-details/{id}',       [InventoryApiController::class, 'getProductDetails']);

        Route::apiResource('sales',                   SaleController::class);

        Route::get('edit-bar-sale/{sale_id}',         [SaleController::class,         'editBarSale']);
        Route::post('update-bar-sale',                [SaleController::class,         'updateBarSale']);

        Route::get('edit-restaurant-sale/{sale_id}',  [SaleController::class,         'editRestaurantSale']);
        Route::post('update-restaurant-sale',         [SaleController::class,         'updateRestaurantSale']);

    });


});
