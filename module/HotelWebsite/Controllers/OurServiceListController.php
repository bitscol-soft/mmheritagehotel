<?php

namespace Module\HotelWebsite\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelWebsite\Models\OurServiceList;

class OurServiceListController extends Controller
{

    public function index()
    {
        $this->hasAccess("ourservicelists.index");
        $service = OurServiceList::where('status', 1)->get();

        return view('our_service.service_list.index',compact('service'));
    }



    public function create()
    {
        $this->hasAccess("ourservicelists.create");
        return view('our_service.service_list.create');
    }



    public function edit($id)
    {
        $this->hasAccess("ourservicelists.edit");
        $service = OurServiceList::find($id);
        return view('our_service.service_list.edit',compact('service'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'service_title'        => 'required',
            'service_short_desc'   => 'required',
            'service_icon'         => 'required',
        ]);

        try {

            OurServiceList::create([
                'service_title'       =>  $request->service_title,
                'service_description' =>  $request->service_short_desc,
                'service_icon'        =>  $request->service_icon,
                'service_list'        =>  $request->service_list,
                'company_id'          =>  1,
                'created_by'          =>  auth()->id()

            ]);


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.our_service_list.index')->with('message', 'Our Service List Create Successfull');
    }



    public function update(Request $request,$id)
    {
        $request->validate([
            'service_title'        => 'required',
            'service_short_desc'   => 'required',
            'service_icon'         => 'required',
        ]);

        $service = OurServiceList::find($id);

        try {

            $service->update([
                'service_title'       =>  $request->service_title,
                'service_description' =>  $request->service_short_desc,
                'service_icon'        =>  $request->service_icon,
                'service_list'        =>  $request->service_list,
                'status'              =>  $request->status,
                'company_id'          =>  1,
                'updated_by'          =>  auth()->id()

            ]);


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('website-core.our_service_list.index')->with('message', 'Our Service List Update Successfull');
    }


    public function destroy($id)
    {

        $this->hasAccess("ourservicelists.delete");

        try {

            $service = OurServiceList::find($id);
            $service->delete();

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('website-core.our_service_list.index')->with('message', 'Our Service List Delete Successfull');
    }
}
