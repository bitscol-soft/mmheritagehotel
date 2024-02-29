<?php

namespace Module\HotelWebsite\Controllers;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\Page;
use Module\HotelWebsite\Models\AboutSection;
use Rajib\LaravelSlugGenerator\Facades\SlugGenerator;


class PageController extends Controller
{

    use FileSaver;

    public function index()
    {
        $data['pages'] = Page::active()->get();
        return view('pages.index', $data);
    }



    public function create()
    {
        return view('pages.create');
    }




    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required',
        ]);

        try {
            $this->updateOrCreate($request);
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.pages.index')->with('message', 'Page Section Create Successfull');
    }


    public function edit($id)
    {
        $data['page'] = Page::find($id);
        return view('pages.edit', $data);
    }


    public function update(Request $request, $id)
    {
        try {
            $this->updateOrCreate($request, $id);

            return redirect()->route('website-core.pages.index')->with('success', 'Updated Success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $page = Page::find($id);
            $page->delete();

            return redirect()->back()->with('success', 'Deleted Success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    public function updateOrCreate($request, $id = null)
    {
        $page = Page::updateOrCreate([
            'id'    => $id,
        ], [
            'title'                 => $request->title,
            'slug'                  => SlugGenerator::generate(Page::class, $request->title, 'slug'),
            'sub_title'             => $request->sub_title,
            'short_description'     => $request->short_description,
            'description'           => $request->description,
            'status'                => $request->status ?? 1,
            'company_id'            => $request->company_id,
            'created_by'            => $request->created_by,

        ]);

        // $this->UploadWebp($request->image, $page, 'image', 'uploads/hotel/website/page', 1140, 655);
        $this->upload_file($request->image, $page, 'image', 'uploads/hotel/website/page');
    }



}
