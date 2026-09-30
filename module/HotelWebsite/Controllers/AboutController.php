<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FileSaver;
use Module\HotelWebsite\Models\AboutSection;


class AboutController extends Controller
{

    use FileSaver;

    public function create()
    {
        $this->hasAccess("aboutsections.index");

        $about = AboutSection::first();

        return view('about.create',compact('about'));
    }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'heading_title'        => 'required',
    //         'heading_description'  => 'required',
    //         'short_box_heading'    => 'required',
    //         'short_box_desc'       => 'required',
    //         'first_image'          => 'required',
    //         'second_image'         => 'required',
    //     ]);

    //     try {

    //         $uploader = new Filesaver;
    //         $about    = AboutSection::create([
    //             'about_heading'         => $request->heading_title,
    //             'about_description'     => $request->heading_description,
    //             'offer_title'           => $request->short_box_heading,
    //             'offer_description'     => $request->short_box_desc,
    //             'company_id'            => 1,
    //             'created_by'            => auth()->id()

    //         ]);
    //         $uploader->UploadWebp($request->first_image, $about, 'first_image', 'uploads/hotel/website', 1140, 455);
    //         $uploader->UploadWebp($request->second_image, $about, 'second_image', 'uploads/hotel/website', 600, 420);


    //     } catch (\Throwable $e) {

    //         return redirect()->back()->with('error', $e->getMessage());
    //     }

    //     return redirect()->back()->with('message', 'About Section Update Successfull');
    // }


    public function store(Request $request)
    {
        $request->validate([
            'heading_title'        => 'required',
            'heading_description'  => 'required',
            'short_box_heading'    => 'required',
            'short_box_desc'       => 'required',
            // 'first_image'          => 'required',
            // 'second_image'         => 'required',
        ]);

        try {

            $about    = AboutSection::firstOrNew(['id' => optional(AboutSection::first())->id]);

            $about->update([
                'about_heading'         => $request->heading_title,
                'about_description'     => $request->heading_description,
                'offer_title'           => $request->short_box_heading,
                'offer_description'     => $request->short_box_desc,
                'company_id'            => 1,
                'created_by'            => auth()->id()

            ]);
            $this->UploadWebp($request->first_image, $about, 'first_image', 'uploads/hotel/website', 1140, 455);
            $this->UploadWebp($request->second_image, $about, 'second_image', 'uploads/hotel/website', 600, 420);


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('message', 'About Section Update Successfull');
    }
}
