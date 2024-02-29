<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Module\Hotel\Models\Rooms;
use Module\Hotel\Models\Aminities;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\Page;
use Module\Hotel\Models\RoomCategory;
use Module\HotelWebsite\Models\OurService;
use Module\HotelWebsite\Models\AboutSection;
use Module\HotelWebsite\Models\HotelFeature;
use Module\HotelWebsite\Models\HotelGallery;
use Module\HotelWebsite\Models\PrivacyPolicy;
use Module\HotelWebsite\Models\OurServiceList;
use Module\HotelWebsite\Models\HotelFeatureList;

class HomePageController extends Controller
{

    //--------------------------------------------------------------------------//
    //                  HOMEPAGE METHOD FOR SHOW HOMEPAGE VIEW                  //
    //--------------------------------------------------------------------------//
    public function homePage()
    {
        $data['feature_head']  = HotelFeature::first();
        $data['feature_list']  = HotelFeatureList::where('status',1)->get();
        $data['about']         = AboutSection::first();
        $data['service']       = OurService::first();
        $data['service_list']  = OurServiceList::where('status',1)->take(2)->get();
        $data['room_category'] = RoomCategory::where('status',1)->get();
        $data['gallery']       = HotelGallery::where('status',1)->get();

        return view('frontend.home',$data);
    }






    //--------------------------------------------------------------------------//
    //                         VIEW A ROOM CATEGORY METHOD                      //
    //--------------------------------------------------------------------------//
    public function viewCategory(Request $request)
    {
        // return $request->all();

        if ($request->room_category == 'all') {
            $data['room_categories']  = RoomCategory::where('status',1)->get();

            return view('frontend.search_all_room', $data);
        }


        $data['category']   = RoomCategory::find($request->room_category);
        $get_aminity        = $data['category']->room_aminities;
        $aminities_list     = explode(',', $get_aminity);

        $data['aminities']  = [];
        $data['check_in']   = $check_in  = date('Y-m-d', strtotime($request->check_in));
        $data['check_out']  = $check_out = date('Y-m-d', strtotime($request->check_out));


        foreach ($aminities_list as $key => $value) {

            $name = Aminities::where('id',$value)->first();

            if ($name != null || $name != '') {
                array_push($data['aminities'],$name[0]);
            }

        }



        $room        =  Rooms::where('room_category', $data['category']->id);
        $totalRoom   = $room->get()->count();

        $bookingRoom = $room->whereHas('booking_dates', function($q) use ($check_in, $check_out){
                                $q->where(function ($q) use ($check_in, $check_out) {
                                    $q->where('date', '>=', $check_in)
                                      ->where('date', '<=', $check_out);
                                });
                            })->get();


        $data['totalAvailableRoom'] = $bookingRoom != null ? $totalRoom - $bookingRoom->count() : $totalRoom;


        if ($data['totalAvailableRoom'] > 0) {

            $availableRoom = getAvailableRoom($data['category']->id, $data['check_in'], $data['check_out']);

            foreach($availableRoom as $key => $item) {

                if ( $item->is_booked == 0 && $item->is_checkin == 0 && $item->is_reservation == 0 ) {
                    $data['room_id'] = $item->id;
                    break;
                }else{
                    continue;
                }
            }

        }

        return view('frontend.search_room',$data);

    }








    //--------------------------------------------------------------------------//
    //                  VIEWROOM METHOD FOR VIEW ROOM PAGE                      //
    //--------------------------------------------------------------------------//
    public function viewRoom($url_slug)
    {
        $room = RoomCategory::where('url_slug', $url_slug)->first();

        $aminities_list = collect(explode(',', $room->room_aminities))->toArray();

        $aminities = Aminities::whereIn('id', $aminities_list)->get();


        return view('frontend.room_view',compact('room','aminities'));
    }







    //--------------------------------------------------------------------------//
    //          TERMS CONDITION METHOD FOR SHOW TERMS & CONDITION PAGE          //
    //--------------------------------------------------------------------------//
    public function termsCondition()
    {
        $data = PrivacyPolicy::first();

        return view('frontend.terms',compact('data'));
    }








    //--------------------------------------------------------------------------//
    //          PRIVACY & POLICY METHOD FOR SHOW PRIVACY & POLICY PAGE          //
    //--------------------------------------------------------------------------//
    public function privacyPolicy()
    {
        $data = PrivacyPolicy::first();

        return view('frontend.privacy_policy',compact('data'));
    }



    //--------------------------------------------------------------------------//
    //          Single Page METHOD         //
    //--------------------------------------------------------------------------//
    public function singlePage($slug)
    {
        $data['page'] = Page::where('slug', $slug)->first();

        return view('frontend.single-page-view', $data);
    }





    //--------------------------------------------------------------------------//
    //                      CHECK AVAILABLE ROOM METHOD                         //
    //--------------------------------------------------------------------------//
    public function checkAvailableRoom(Request $request)
    {
        // return $request->all();

        $check_in    = $request->check_in;
        $check_out   = $request->check_out;

        $room        =  Rooms::where('room_category', $request->category_id);

        $totalRoom   = $room->get()->count();

        $bookingRoom = $room->whereHas('booking_dates', function($q) use ($check_in, $check_out){
                                $q->where(function ($q) use ($check_in, $check_out) {
                                    $q->where('date', '>=', $check_in)
                                      ->where('date', '<=', $check_out);
                                });
                            })->get();


        $data['totalAvailableRoom'] = $bookingRoom != null ? $totalRoom - $bookingRoom->count() : $totalRoom;


        if ($data['totalAvailableRoom'] > 0) {

            $availableRoom = getAvailableRoom($request->category_id, $check_in, $check_out);

            foreach($availableRoom as $key => $item) {

                if ( $item->is_booked == 0 && $item->is_checkin == 0 && $item->is_reservation == 0 ) {
                    $data['room_id'] = $item->id;
                    break;
                }else{
                    continue;
                }
            }

        }

        return $data;

    }



}
