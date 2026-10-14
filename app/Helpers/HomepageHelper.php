<?php

//  ====== Homepage Banner =====  //

use Module\Hotel\Models\Vat;
use App\Models\CompanyDetails;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Aminities;
use Module\HotelWebsite\Models\Page;
use Module\Hotel\Models\RoomCategory;
use Module\HotelWebsite\Models\HotelBanner;
use Module\HotelWebsite\Models\WebsiteSetting;

if (!function_exists('getBanner')) {
    function getBanner() {

        $data = HotelBanner::where('status', 1 )->get();
       return $data;
    }
}

if (!function_exists('websiteInfo')) {

    function websiteInfo() {

        $web_info = WebsiteSetting::first() ?? new WebsiteSetting();
        return $web_info;
    }

    function companyInfo() {

        $companyInfo = CompanyDetails::pluck('footer')->first();
        return $companyInfo;
    }

}

function pages() {

    return Page::active()->get();
}


if (!function_exists('roomCategory')) {

    function roomCategory() {

        $room_category = RoomCategory::where('status',1)->get();

        return $room_category;

    }
}


if (!function_exists('hotelVat')) {

    function hotelVat() {

        $hotel_vat = Vat::first() ?? new Vat(['hotel_vat' => 0]);

        return $hotel_vat;

    }
}




function roomAminities($id) {

    $room = RoomCategory::where('id', $id)->first();
    if (!$room) {
        return collect();
    }

    $aminities_list = collect(explode(',', $room->room_aminities))->toArray();

    return Aminities::whereIn('id', $aminities_list)->get();

}
