<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\HotelFeatureList;

class HotelFeatureListController extends Controller
{








    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD FOR LIST HOMEPAGE FEATURE
     |--------------------------------------------------------------------------
    */

    public function index()
    {
        $this->hasAccess("featurelists.index");
        $feature_list = HotelFeatureList::get();

        return view('hotel_feature.feature_list.index',compact('feature_list'));
    }









    /*
     |--------------------------------------------------------------------------
     | CREATE METHOD FOR SHOW CREATE PAGE
     |--------------------------------------------------------------------------
    */

    public function create()
    {
        $this->hasAccess("featurelists.create");
        return view('hotel_feature.feature_list.create');
    }








   /*
     |--------------------------------------------------------------------------
     | EDIT METHOD FOR EDIT DATA
     |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $this->hasAccess("featurelists.edit");
        $data = HotelFeatureList::find($id);

        return view('hotel_feature.feature_list.edit',compact('data'));

    }














    /*
     |--------------------------------------------------------------------------
     | STORE METHOD FOR SAVE DATA
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'feature_list_title'     => 'required',
            'feature_list_subtitle'  => 'required',
            'feature_icon'           => 'required',
        ]);

        try {

            HotelFeatureList::create([

                'title'         => $request->feature_list_title,
                'sub_title'     => $request->feature_list_subtitle,
                'feature_icon'  => $request->feature_icon,
                'company_id'    => 1,
                'created_by'    => auth()->id()

            ]);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

        return redirect()->route('website-core.feature_list.index')->with('message', 'Feature List Create Successfull');
    }












    /*
     |--------------------------------------------------------------------------
     | UPDATE METHOD FOR UPDATE DATA
     |--------------------------------------------------------------------------
    */

    public function update(Request $request,$id)
    {
        $request->validate([
            'feature_list_title'     => 'required',
            'feature_list_subtitle'  => 'required',
            'feature_icon'           => 'required',
        ]);

        $data = HotelFeatureList::find($id);

        try {

            $data->update([

                'title'         => $request->feature_list_title,
                'sub_title'     => $request->feature_list_subtitle,
                'feature_icon'  => $request->feature_icon,
                'status'        => $request->status,
                'company_id'    => 1,
                'created_by'    => auth()->id()

            ]);


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }
        return redirect()->route('website-core.feature_list.index')->with('message', 'Feature List Update Successfull');

    }











    /*
     |--------------------------------------------------------------------------
     | DELETE METHOD FOR DELETE DATA
     |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        
        $this->hasAccess("featurelists.delete");

        try {

            $data = HotelFeatureList::find($id);
            $data->delete();

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }
        return redirect()->route('website-core.feature_list.index')->with('message', 'Feature List Update Successfull');

    }


}

