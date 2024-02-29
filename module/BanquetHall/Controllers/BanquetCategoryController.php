<?php

namespace Module\BanquetHall\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Module\BanquetHall\Models\BanquetAmenitie;
use Module\BanquetHall\Models\BanquetCategory;

class BanquetCategoryController extends Controller
{
    public function index()
    {

        $this->hasAccess("hall-categories.index");

        $data = BanquetCategory::latest()->get();
        return view('hall.category.index', compact('data'));
    }







    public function create()
    {

        $this->hasAccess("hall-categories.create");

        $aminities = BanquetAmenitie::get();


        return view('hall.category.create', compact('aminities'));
    }











    public function edit($id)
    {

        $this->hasAccess("hall-categories.edit");

        $category           = BanquetCategory::find($id);
        $aminities_item     = explode(',', $category->room_aminities);
        $aminities          = BanquetAmenitie::get();


        return view('hall.category.edit', compact('category', 'aminities', 'aminities_item'));
    }











    public function store(Request $request)
    {
        $request->validate([
            'cat_name'          => 'required',
            'aminities.*'       => 'required',
            // 'price'             => 'required|numeric',
            'status'            => 'required',
            // 'cat_name'          => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $aminities  = $request->aminities;

                // $slug       = Str::slug(strtolower($request->cat_name));

                // $check      = BanquetCategory::where('url_slug', $slug)->first();

                // if ($check) {

                //     $slug = $slug . '-' . rand(1, 99);
                // }
                DB::transaction(function () use ($request) {
                    $category   = BanquetCategory::create([

                        'name'                      => $request->cat_name,
                        'guest_capacity'            => $request->guest_capacity,
                        'description'               => $request->description,
                        // 'room_aminities'            => implode(',', $aminities ?? []),
                        // 'price'                     => convertToBDTCurrency($request->price),
                        // 'vat'                       => $request->vat,
                        'status'                    => $request->status,
                        // 'url_slug'                  => $slug,
                        // 'allow_guest_wise_price'    => $request->allow_guest_wise_price ?? 0

                    ]);

                    // if ($request->guest_capacity > 1 && $request->allow_guest_wise_price) {
                    //     foreach ($request->guest_prices ?? [] as $key => $price) {
                    //         $category->roomPrices()->updateOrCreate([
                    //             'capacity'      => $request->guest_capacities[$key] ?? 1,
                    //             'price'         => convertToBDTCurrency($price),
                    //         ]);
                    //     }
                    // }

                    // if ($request->file('room_photos')) {

                    //     foreach ($request->file('room_photos') as $key => $image) {

                    //         $file_name = 'room_' . date('dmY') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    //         RoomPhotos::create([
                    //             'category_id'   => $category->id,
                    //             'name'          => $file_name,
                    //             'relative_path' => 'assets/uploads/hotel/room/'
                    //         ]);

                    //         $image->move(public_path() . '/assets/uploads/hotel/room/', $file_name);
                    //     }
                    // }
                });
            });
        } catch (\Throwable $e) {

            return redirect()->back()->withInput($request->except('room_photos'))->with('error', $e->getMessage());
        }

        return redirect()->route('banquet.hall-categories.index')->with('message', 'Category Create Successfull');
    }













    public function update(Request $request, $id)
    {

        try {
            $aminities  = $request->aminities;

            // $slug = Str::slug(strtolower($request->cat_name));

            $category   = BanquetCategory::find($id);

            DB::transaction(function () use ($request, $category, $aminities) {

                $category->update([
                    'name'                      => $request->cat_name,
                    'guest_capacity'            => $request->guest_capacity,
                    'description'               => $request->description,
                    'room_aminities'            => implode(',', $aminities ?? []),
                    // 'can_sleep'                 => $request->can_sleep,
                    // 'bed_details'               => $request->bed_details,
                    // 'room_sqft'                 => $request->room_size,
                    // 'price'                     => convertToBDTCurrency($request->price),
                    // 'vat'                       => $request->vat,
                    // 'vat'                       => $request->vat,
                    'status'                    => $request->status,
                    // 'url_slug'                  => $slug,
                    // 'allow_guest_wise_price'    => $request->allow_guest_wise_price ?? 0
                ]);

            });
        } catch (\Throwable $e) {

            DB::rollback();

            return redirect()->back()->with('error', $e->getMessage());
        }
        DB::commit();
        return redirect()->route('banquet.hall-categories.index')->with('message', 'Categories Update Successfull');
    }










    public function destroy($id)
    {

        $this->hasAccess("hall-categories.delete");

        try {
            $room_category = BanquetCategory::find($id);
            // $room_img      = RoomPhotos::select('name')->where('category_id', $id)->get();
            $room_category->delete();

            return redirect()->back()->with('message', 'Room Category deleted Successfull');
        } catch (Exception $ex) {

            return redirect()->back()->with('error', 'Some error, please check');
        }
    }
}
