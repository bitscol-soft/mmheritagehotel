<?php

namespace Module\Hotel\Controllers;

use Exception;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Models\Aminities;
use Module\Hotel\Models\RoomPhotos;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\RoomCategory;
use Module\Hotel\Models\RoomPrice;

class RoomCategoryController extends Controller
{



    public function index()
    {

        $this->hasAccess("categories.index");

        $data = RoomCategory::with('roomPrices', 'roomSingleImg')->get();
        return view('category.index', compact('data'));
    }







    public function create()
    {

        $this->hasAccess("categories.create");

        $aminities = Aminities::get();

        return view('category.create', compact('aminities'));
    }











    public function edit($id)
    {

        $this->hasAccess("categories.edit");

        $category           = RoomCategory::find($id);
        $aminities_item     = explode(',', $category->room_aminities);
        $aminities          = Aminities::get();


        return view('category.edit', compact('category', 'aminities', 'aminities_item'));
    }











    public function store(Request $request)
    {
        $request->validate([
            'cat_name'          => 'required',
            'aminities.*'       => 'required',
            'price'             => 'required|numeric',
            'status'            => 'required',
            'cat_name'          => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $aminities  = $request->aminities;

                $slug       = Str::slug(strtolower($request->cat_name));

                $check      = RoomCategory::where('url_slug', $slug)->first();

                if ($check) {

                    $slug = $slug . '-' . rand(1, 99);
                }
                DB::transaction(function () use ($slug, $aminities, $request) {
                    $category   = RoomCategory::create([

                        'name'                      => $request->cat_name,
                        'guest_capacity'            => $request->guest_capacity,
                        'description'               => $request->description,
                        'room_aminities'            => implode(',', $aminities ?? []),
                        'price'                     => convertToBDTCurrency($request->price),
                        'vat'                       => $request->vat,
                        'status'                    => $request->status,
                        'url_slug'                  => $slug,
                        'allow_guest_wise_price'    => $request->allow_guest_wise_price ?? 0

                    ]);

                    if ($request->guest_capacity > 1 && $request->allow_guest_wise_price) {
                        foreach ($request->guest_prices ?? [] as $key => $price) {
                            $category->roomPrices()->updateOrCreate([
                                'capacity'      => $request->guest_capacities[$key] ?? 1,
                                'price'         => convertToBDTCurrency($price),
                            ]);
                        }
                    }

                    if ($request->file('room_photos')) {

                        foreach ($request->file('room_photos') as $key => $image) {

                            $file_name = 'room_' . date('dmY') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                            RoomPhotos::create([
                                'category_id'   => $category->id,
                                'name'          => $file_name,
                                'relative_path' => 'assets/uploads/hotel/room/'
                            ]);

                            $image->move(public_path() . '/assets/uploads/hotel/room/', $file_name);
                        }
                    }
                });
            });
        } catch (\Throwable $e) {

            return redirect()->back()->withInput($request->except('room_photos'))->with('error', $e->getMessage());
        }

        return redirect()->route('hotel-categories.index')->with('message', 'Category Create Successfull');
    }













    public function update(Request $request, $id)
    {

        try {
            $aminities  = $request->aminities;

            $slug = Str::slug(strtolower($request->cat_name));

            $category   = RoomCategory::find($id);

            DB::transaction(function () use ($request, $slug, $category, $aminities) {

                $category->update([
                    'name'                      => $request->cat_name,
                    'guest_capacity'            => $request->guest_capacity,
                    'description'               => $request->description,
                    'room_aminities'            => implode(',', $aminities ?? []),
                    'can_sleep'                 => $request->can_sleep,
                    'bed_details'               => $request->bed_details,
                    'room_sqft'                 => $request->room_size,
                    'price'                     => convertToBDTCurrency($request->price),
                    'vat'                       => $request->vat,
                    'vat'                       => $request->vat,
                    'status'                    => $request->status,
                    'url_slug'                  => $slug,
                    'allow_guest_wise_price'    => $request->allow_guest_wise_price ?? 0
                ]);

                if ($request->guest_capacity > 1 && $request->allow_guest_wise_price) {
                    foreach ($request->guest_prices ?? [] as $key => $price) {

                        RoomPrice::updateOrCreate([
                            'room_category_id'  => $category->id,
                            'capacity'          => $request->guest_capacities[$key] ?? 1,
                        ],[
                            'price'             => convertToBDTCurrency($price),
                        ]);

                    }
                }

                // if ($request->guest_capacity > 1 && $request->allow_guest_wise_price) {
                //     foreach ($request->guest_prices ?? [] as $key => $price) {
                //         $category->roomPrices()->updateOrCreate([
                //             'capacity'      => $request->guest_capacities[$key] ?? 1,
                //             'price'         => convertToBDTCurrency($price),
                //         ]);
                //     }
                // }


                if ($request->file('room_photos')) {
                    foreach ($request->file('room_photos') as $key => $image) {

                        $file_name = 'room_' . date('dmY') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        RoomPhotos::create([
                            'category_id'   => $category->id,
                            'name'          => $file_name,
                            'relative_path' => 'assets/uploads/hotel/room/'
                        ]);
                        $image->move(public_path() . '/assets/uploads/hotel/room/', $file_name);
                    }
                }

            });
        } catch (\Throwable $e) {

            DB::rollback();

            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('hotel-categories.index')->with('message', 'Categories Update Successfull');
    }










    public function destroy($id)
    {

        $this->hasAccess("categories.delete");

        try {
            $room_category = RoomCategory::find($id);
            $room_img      = RoomPhotos::select('name')->where('category_id', $id)->get();

            foreach ($room_img as $key => $value) {

                $image  = public_path() . '/images/hotel/room/' . $value->name;

                if (file_exists($image)) {
                    unlink($image);
                }
            }

            RoomPhotos::where('category_id', $id)->delete();
            $room_category->roomPrices()->delete();
            $room_category->delete();

            return redirect()->back()->with('message', 'Room Category deleted Successfull');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }
}
