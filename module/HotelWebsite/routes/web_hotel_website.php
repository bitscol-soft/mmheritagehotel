<?php


use Illuminate\Support\Facades\Route;
use Module\HotelWebsite\Controllers\AboutController;
use Module\HotelWebsite\Controllers\HotelBannerController;
use Module\HotelWebsite\Controllers\HotelFeatureController;
use Module\HotelWebsite\Controllers\HotelFeatureListController;
use Module\HotelWebsite\Controllers\HotelGalleryController;
use Module\HotelWebsite\Controllers\OurServiceController;
use Module\HotelWebsite\Controllers\OurServiceListController;
use Module\HotelWebsite\Controllers\PageController;
use Module\HotelWebsite\Controllers\PrivacyPolicyController;
use Module\HotelWebsite\Controllers\WebsiteSettingController;


// Hotel Routes
Route::group(['prefix' => 'hotel-website', 'as'=> 'website-core.'], function () {

    Route::resource('feature-lists',    HotelFeatureListController::class)->names('feature_list');
    Route::resource('services',         OurServiceController::class)->names('our_service');
    Route::resource('service-lists',    OurServiceListController::class)->names('our_service_list');
    Route::resource('about-us',         AboutController::class)->names('about_section');
    Route::resource('settings',         WebsiteSettingController::class);
    Route::resource('privacy-policy',   PrivacyPolicyController::class)->names('privacy_poilicy');


    Route::resources([
        'banner'          => HotelBannerController::class,
        'feature'         => HotelFeatureController::class,
        'gallery'          => HotelGalleryController::class,
        'pages'          => PageController::class,
    ]);



});
