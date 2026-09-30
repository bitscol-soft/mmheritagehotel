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

        $feature = HotelFeature::firstOrNew([]);

        return view('hotel_feature.create', compact('feature'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'feature_title'    => 'required',
            'feature_subtitle' => 'required',
        ]);

        try {
            HotelFeature::create([
                'title'      => $request->feature_title,
                'sub_title'  => $request->feature_subtitle,
                'company_id' => 1,
                'created_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('message', 'Feature Heading Saved Successfully');
    }










    public function update(Request $request,$id)
    {
        $request->validate([
            'feature_title'     => 'required',
            'feature_subtitle'  => 'required',
        ]);

        try {

            $feature = HotelFeature::find(request()->route('feature')) ?? HotelFeature::firstOrNew([]);

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
