<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Skycoder\LaravelFilesaver\Filesaver;
use Module\HotelWebsite\Models\OurService;

class OurServiceController extends Controller
{
    public function create()
    {
        $this->hasAccess("ourservices.create");

        $service = OurService::first();
        return view('our_service.create',compact('service'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading_title'       => 'required',
            'section_background'  => 'required',
        ]);

        try {

            $uploader   = new Filesaver;
            $service    = OurService::create([

                'service_heading' => $request->heading_title,
                'company_id'      => 1,
                'created_by'      => auth()->id()
            ]);

            $uploader->upload_file($request->section_background, $service, 'service_background_img', 'uploads/hotel/website');


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

        return redirect()->back()->with('message', 'About Section Update Successfull');
    }
}
