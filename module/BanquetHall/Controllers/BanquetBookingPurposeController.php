<?php

namespace Module\BanquetHall\Controllers;

use Exception;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BanquetBookingPurposeController extends Controller
{
    public function index(Request $request)
    {
        $data['booking_purpose'] = BookingPurpose::active()
            ->SearchByField('name');
            // ->get();

        return view('booking-purpose.index', $data);
    }



    public function create()
    {
        return view('booking-purpose.create');
    }



    public function store(Request $request)
    {
        try {
            $url = $request->rule == 1 ? 'purpose' : 'platform';
            $this->updateOrCreate($request);
            return redirect()->route('booking-purpose.index','type='.$url)->with('success', 'Booking Purpose Created Success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }



    public function edit($id){
        $data['booking_purpose'] = BookingPurpose::findOrFail($id);
        return view('booking-purpose.edit', $data);
    }




    public function update(Request $request, $id)
    {
        try {
            $url = $request->rule == 1 ? 'purpose' : 'platform';
            $this->updateOrCreate($request, $id);
            return redirect()->route('booking-purpose.index','type='.$url)->with('success', 'Booking Purpose Update Success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }




    public function updateOrCreate($request, $id = null)
    {
        $data = BookingPurpose::updateOrCreate([
            'id'        => $id
        ], [
            'name'      => $request->name,
            'rule'      => $request->rule,
        ]);
    }


    public function destroy($id){
        try {
            BookingPurpose::find($id)->delete();
            return redirect()->back()->with('success', 'Delete Success');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
