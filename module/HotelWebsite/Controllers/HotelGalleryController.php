<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\HotelGallery;
use Skycoder\LaravelFilesaver\Filesaver;

class HotelGalleryController extends Controller
{

    public function index()
    {

        $this->hasAccess("galleries.index");

        $data = HotelGallery::get();

        return view('gallery.index',compact('data'));
    }









    public function create()
    {

        $this->hasAccess("galleries.create");
        return view('gallery.create');
    }










    public function edit($id)
    {

        $this->hasAccess("galleries.edit");
        $gallery = HotelGallery::find($id);

        return view('gallery.edit',compact('gallery'));
    }









    public function store(Request $request)
    {

        $request->validate([
            'gallery_images' => 'required',
        ]);

        $uploader = new Filesaver;

        try {

            $gallery = HotelGallery::create([

                'gallery_text' => $request->gallery_title,
                'status'       => 1,
                'company_id'   => 1,
                'created_by'   => 1,
            ]);

            $uploader->upload_file($request->gallery_images, $gallery, 'name', 'uploads/hotel/website/gallery');

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

        return redirect()->route('website-core.gallery.index')->with('message', 'Gallery Create Successfull');

    }



    public function update(Request $request,$id)
    {

        $uploader = new Filesaver;

        try {

            $gallery = HotelGallery::find($id);

            $gallery->update([

                'gallery_text' => $request->gallery_title,
                'status'       => 1,
                'company_id'   => 1,
                'updated_by'   => 1,
            ]);

            $uploader->upload_file($request->gallery_images, $gallery, 'name', 'uploads/hotel/website/gallery');

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }

        return redirect()->route('website-core.gallery.index')->with('message', 'Gallery Update Successfull');
    }




    public function destroy($id)
    {

        $this->hasAccess("galleries.delete");

        try {

            $gallery = HotelGallery::find($id);
            $gallery->delete();

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.gallery.index')->with('message', 'Gallery Delete Successfull');

    }

}
