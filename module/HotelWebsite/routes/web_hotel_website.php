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

    Route::resource('feature-lists', HotelFeatureListController::class)->names('feature_list')->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::resource('services', OurServiceController::class)->names('our_service')->except(['destroy', 'edit', 'index', 'show', 'update']); // round 3: destroy/edit/index/show/update not implemented (docs/BUGS.md)
    Route::resource('service-lists', OurServiceListController::class)->names('our_service_list')->except(['show']); // round 3: show not implemented (docs/BUGS.md)
    Route::resource('about-us', AboutController::class)->names('about_section')->except(['destroy', 'edit', 'index', 'show', 'update']); // round 3: destroy/edit/index/show/update not implemented (docs/BUGS.md)
    Route::resource('settings', WebsiteSettingController::class)->except(['create', 'destroy', 'edit', 'show', 'update']); // round 3: create/destroy/edit/show/update not implemented (docs/BUGS.md)
    Route::resource('privacy-policy', PrivacyPolicyController::class)->names('privacy_poilicy')->except(['create', 'destroy', 'edit', 'show', 'update']); // round 3: create/destroy/edit/show/update not implemented (docs/BUGS.md)


    Route::resources([
    ]);
            // round 3 (docs/BUGS.md): HotelBannerController does not implement show; the routes would 500
            Route::resource('banner', HotelBannerController::class)->except(['show']);
            // round 3 (docs/BUGS.md): HotelFeatureController does not implement destroy/edit/index/show; the routes would 500
            Route::resource('feature', HotelFeatureController::class)->except(['destroy', 'edit', 'index', 'show']);
            // round 3 (docs/BUGS.md): HotelGalleryController does not implement show; the routes would 500
            Route::resource('gallery', HotelGalleryController::class)->except(['show']);
            // round 3 (docs/BUGS.md): PageController does not implement show; the routes would 500
            Route::resource('pages', PageController::class)->except(['show']);



});
