<?php

namespace App\Http\Controllers\Front;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\Hotel\Models\RoomPhotos;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\RoomCategory;
use Illuminate\Support\Facades\Cookie;
use Module\Hotel\Models\NightAuditRoomDetail;

class BookingController extends Controller
{


    public function bookingCart()
    {
        return view('frontend.booking-cart');
    }


    // Ajax control [ Add to cart function ]

    public function addToCart(Request $request)
    {

        try{

            $category_id  = $request->category_id;

            if (NightAuditRoomDetail::query()->where('date', $request->check_in_date)->count() > 0) {
                return response()->json([
                    'return'        => 0, 
                    'booking_item'  => [],
                    'status'        => 'Unable to booked.',
                    'cart_count'    => []
                ]);

            }
            if (Cookie::get('booking_cart')) {

                $cookie_data = \stripslashes(Cookie::get('booking_cart'));
                $cart_data   = \json_decode($cookie_data, true);

            }else{

                $cart_data = array();
            }

            $item_id_list = \array_column($cart_data,'item_id');
            $if_prod_id   = $category_id;

            if (\in_array($if_prod_id, $item_id_list)) {

                foreach ($cart_data as $keys => $value) {

                    if ($cart_data[$keys]["item_id"] == $category_id) {

                        $item_data = json_encode($cart_data);
                        $item_dats =json_decode($item_data);
                        $minutes = 1440;
                        Cookie::queue(Cookie::make('booking_cart', $item_data, $minutes));

                        return response()->json(['status' => $cart_data[$keys]["item_name"] ." Already Added to Cart!",'return'=>0]);

                    }
                }
            }else {

                $category     = RoomCategory::find($category_id);
                $category_img = RoomPhotos::where('category_id',$category_id)->first();
                $nights       = $request->nights;
                $price        = $category->price * $nights;

                $prod_name       = $category->name;
                $prod_price      = $price;
                $prod_img        = $category_img->name;
                $prod_img_path   = $category_img->relative_path;

                $date            = explode('-', $request->date);
                
                $check_in        = $request->check_in_date ?? Carbon::parse(trim($date[0]))->format('Y-m-d');
                $check_out       = $request->check_out_date ?? Carbon::parse(trim($date[1]))->format('Y-m-d');
                $cart_count      = 0;

                if ($category) {

                    $item_array = array(

                        'item_id'             => $category_id,
                        'item_name'           => $prod_name,
                        'item_price'          => $prod_price,
                        'item_img'            => $prod_img,
                        'item_img_path'       => $prod_img_path,
                        'check_in'            => $check_in,
                        'check_out'           => $check_out,
                        'nights'              => $nights,
                        'guest_capacity'      => $request->guest_capacity,

                    );

                    $cart_data[] = $item_array;

                    $added_item  = $item_array;
                    $item_data   = \json_encode($cart_data);
                    $minutes     = 1440;
                    Cookie::queue(Cookie::make('booking_cart', $item_data, $minutes));


                    if (Cookie::get('booking_cart')) {

                        $cookie_data = \stripslashes(Cookie::get('booking_cart'));
                        $cart_data   = \json_decode($cookie_data, true);
                        $cart_count  = count($cart_data);
                        $cart_count++;

                    }else{
                        $cart_count = 0;
                    }

                    return response()->json(['return'=>1,'booking_item'=>$added_item,'status'=>'Add to cart Successfully','cart_count'=>$cart_count]);
                }
            }

        }catch (\Exception $e) {

        }
    }




    // Ajax control remove from cart

    public function removeToCart(Request $request)
    {
        $prod_id            = $request->booking_id;
        $cookie_data        = stripslashes(Cookie::get('booking_cart'));
        $cart_data          = json_decode($cookie_data, true);
        $item_id_list       = array_column($cart_data, 'item_id');
        $prod_id_is_there   = $prod_id;


        if(in_array($prod_id_is_there, $item_id_list))
        {
            foreach($cart_data as $keys => $values)
            {
                if($cart_data[$keys]["item_id"] == $prod_id)
                {
                    unset($cart_data[$keys]);
                    $item_data = json_encode($cart_data);
                    $minutes = 1440;

                    Cookie::queue(Cookie::make('booking_cart', $item_data, $minutes));

                    if (Cookie::get('booking_cart')) {
                        $cookie_data = \stripslashes(Cookie::get('booking_cart'));
                        $cart_data = \json_decode($cookie_data, true);
                        $cart_count = count($cart_data);
                        $cart_count--;
                    }else{

                    }
                    return response()->json(['status'=>'Item Removed from Cart','cart_count'=>$cart_count]);
                }
            }
        }
    }
}
