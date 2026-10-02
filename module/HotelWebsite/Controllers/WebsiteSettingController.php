<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\WebsiteSetting;
use Module\HotelWebsite\Services\HotelWebsiteService;

class WebsiteSettingController extends Controller
{

    private $hotel_service;

    public function __construct()
    {
        $this->hotel_service = new HotelWebsiteService();
    }



    public function index()
    {
        $this->hasAccess("websitesettings.create");
        $setting = WebsiteSetting::firstOrNew([]);

        return view('site_setting.index',compact('setting'));
    }












    public function store(Request $request)
    {

        $request->validate([
            'website_first_name'        => 'required',
            'website_last_name'         => 'required',
            'phone_no'                  => 'required',
            'email'                     => 'required',

        ]);

        try {

            $this->hotel_service->StoreWebsiteSetting($request);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('message', 'Website Setting Update Successfull');
    }
}
