<?php

namespace Module\HotelWebsite\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\HotelFeature;

class HotelFeatureController extends Controller
{


    public function create()
    {
        $this->hasAccess("features.create");

        $feature = HotelFeature::first();

        return view('hotel_feature.create',compact('feature'));
    }










    public function update(Request $request,$id)
    {
        $request->validate([
            'feature_title'     => 'required',
            'feature_subtitle'  => 'required',
        ]);

        try {

            $feature = HotelFeature::first();

            $feature->update([

                'title'      => $request->feature_title,
                'sub_title'  => $request->feature_subtitle,
                'company_id' => 1,
                'created_by' => auth()->id(),

            ]);

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('message', 'Feature Heading Update Successfull');

    }
}
