<?php

namespace Module\HotelService\Controllers\Services;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Module\HotelService\Models\Service\HotelService;

class HotelServiceController extends Controller
{

    // THIS IS MEDICAL SERVICE

    public function index(Request $request)
    {
        $this->hasAccess("hotel.services.index");

        $data['services'] = HotelService::companies()->paginate(25);

        return view('services.category.index', $data);
    }



    public function store(Request $request)
    {
        $this->hasAccess("hotel.services.create");

        $data = $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);
        try {
            HotelService::create($data);
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Data created success');
    }


    public function update(Request $request, $id)
    {
        $this->hasAccess("hotel.services.edit");


        try {
            HotelService::find($id)->update($request->all());
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Data update success');
    }


    public function destroy($id)
    {
        $this->hasAccess("hotel.services.delete");

        try {

            HotelService::find($id)->delete();
        } catch (\Throwable $th) {

            return redirect()->back()->withError($th->getMessage());
        }

        return redirect()->back()->withMessage('Data deleted success');
    }
}
