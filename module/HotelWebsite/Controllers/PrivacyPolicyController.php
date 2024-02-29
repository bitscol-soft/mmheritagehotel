<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $this->hasAccess("privacypoilicies.create");
        $our_privacy = PrivacyPolicy::first();
        return view('privacy_policy.index',compact('our_privacy'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'privacy_header'        => 'required',
            'terms_header'          => 'required',
        ]);

        try {


            $get_data = PrivacyPolicy::first() ?? 0;


            if ($get_data == !null) {

                $get_data->update([

                    'privacy_header_title'  => $request->privacy_header,
                    'terms_header_title'    => $request->terms_header,
                    'privacy_policy'        => $request->privacy_details,
                    'terms_condition'       => $request->terms_details,
                    'company_id'            => 1,
                    'updated_by'            => auth()->id(),

                ]);

            }else {

                PrivacyPolicy::firstOrCreate(['id' => 1],[

                    'privacy_header_title'  => $request->privacy_header,
                    'terms_header_title'    => $request->terms_header,
                    'privacy_policy'        => $request->privacy_details,
                    'terms_condition'       => $request->terms_details,
                    'company_id'            => 1,
                    'created_by'            => auth()->id(),
                ]);

            }




        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

        return redirect()->back()->with('message', 'Privacy Policy Update Successfull');
    }
}
