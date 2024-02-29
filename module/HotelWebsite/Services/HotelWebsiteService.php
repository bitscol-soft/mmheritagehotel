<?php


namespace Module\HotelWebsite\Services;

use App\Traits\FileSaver;
use Module\HotelWebsite\Models\HotelBanner;
use Module\HotelWebsite\Models\WebsiteSetting;
use Module\HotelWebsite\Models\HotelBannerImage;

class HotelWebsiteService
{
    use FileSaver;


    public function SaveBanner($request)
    {
        $banner   = HotelBanner::create([

            'banner_title'       => $request->banner_head_title,
            'banner_sub_title'   => $request->banner_sub_title,
            'banner_short_desc'  => $request->banner_short_desc,
            'status'             => $request->status,
            'company_id'         => 1,
            'created_by'         => auth()->id(),
        ]);

        $this->upload_file($request->banner_photos, $banner, 'banner_image', 'uploads/hotel/website/');

    }





    public function UpdateBanner($request,$id)
    {
        $banner   = HotelBanner::find($id);

        $banner->update([

            'banner_title'       => $request->banner_head_title,
            'banner_sub_title'   => $request->banner_sub_title,
            'banner_short_desc'  => $request->banner_short_desc,
            'status'             => $request->status,
            'company_id'         => 1,
            'updated_by'         => auth()->id(),
        ]);

        $this->upload_file($request->banner_photos, $banner, 'banner_image', 'uploads/hotel/website/');
    }







    public function StoreWebsiteSetting($request)
    {

        $setting = WebsiteSetting::first();

        $setting->update([

            'site_first_name'    =>  $request->website_first_name,
            'site_last_name'     =>  $request->website_last_name,
            'site_slogan'        =>  $request->website_slogan,
            'phone_no'           =>  $request->phone_no,
            'email'              =>  $request->email,
            'address'            =>  $request->address,
            'location_map'       =>  $request->location_map,
            'facebook_url'       =>  $request->facebook_url,
            'twitter_url'        =>  $request->twitter_url,
            'youtube_url'        =>  $request->youtube_url,
            'linkedin_url'       =>  $request->linkedin_url,
            'meta_keyword'       =>  $request->meta_keywords,
            'meta_description'   =>  $request->meta_description,
            'company_id'         =>  1,
            'created_by'         =>  auth()->id(),
        ]);
    }

}
