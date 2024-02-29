<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\HotelBanner;
use Module\HotelWebsite\Services\HotelWebsiteService;

class HotelBannerController extends Controller
{

    private $hotel_service;

    public function __construct()
    {
        $this->hotel_service = new HotelWebsiteService();
    }






    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->hasAccess("banners.index");
        $data = HotelBanner::get();

        return view('banner.index',compact('data'));
    }













    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->hasAccess("banners.create");
        return view('banner.create');
    }








 /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $this->hasAccess("banners.edit");

        $data = HotelBanner::find($id);

        return view('banner.edit',compact('data'));
    }



    /*
     |--------------------------------------------------------------------------
     | STORE METHOD FOR SAVE BANNER DATA
     |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        // $request->validate([
        //     'banner_head_title' => 'required',
        //     'banner_sub_title'  => 'required',
        //     'banner_short_desc' => 'required',
        // ]);

        try {
            // dd($request->all());

            $this->hotel_service->SaveBanner($request);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.banner.index')->with('message', 'Banner Create Successfull');
    }








    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD FOR UPDATE BANNER DATA
     |--------------------------------------------------------------------------
    */


    public function update(Request $request,$id)
    {
        // return $request->all();

        // $request->validate([
        //     'banner_head_title' => 'required',
        //     'banner_sub_title'  => 'required',
        //     'banner_short_desc' => 'required',
        // ]);

        try {

            $this->hotel_service->UpdateBanner($request,$id);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.banner.index')->with('message', 'Banner Update Successfull');


    }








    /*
     |--------------------------------------------------------------------------
     | DELETE METHOD FOR DELETE BANNER DATA
     |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $this->hasAccess("banners.delete");

        $banner = HotelBanner::find($id);
        try {
            $banner = HotelBanner::find($id);

                if(file_exists($banner->banner_image))
                {
                    unlink($banner->banner_image);
                }
            $banner->delete();

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.banner.index')->with('message', 'Banner Delete Successfull');
    }
}
