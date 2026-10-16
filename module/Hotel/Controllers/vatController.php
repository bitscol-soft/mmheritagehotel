<?php

namespace Module\Hotel\Controllers;



use Illuminate\Http\Request;
use Module\Hotel\Models\Vat;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;

class vatController extends Controller
{




    /**
     * ------------------------------------------------------------------
     * INDEX METHOD
     * ------------------------------------------------------------------
     */
    public function index()
    {

        $this->hasAccess("vats.index");
        $data['systemSetting'] = SystemSetting::where('key', 'use_vat_included')->first();
        $data['vat'] = Vat::first() ?? new Vat();   // docs/BUGS.md #73
        // dd($data['systemSetting']);
        return view('vat.index', $data);
    }







    /**
     * ------------------------------------------------------------------
     * UPDATE METHOD
     * ------------------------------------------------------------------
     */
    public function update(Request $request, $id)
    {

        try {

            // docs/BUGS.md #73: a deployment with no `vats` row posts to /vat/0 - create the
            // row instead of updating a null (the catch block used to swallow this and the
            // screen silently kept the old values).
            $vat = Vat::find($id) ?? new Vat();

            $vat->fill([

                'hotel_vat'             => $request->hotel_vat,
                'resturent_vat'         => $request->resturent_vat,
                'bar_vat'               => $request->bar_vat,
                'vat_number'            => $request->vat_number,
                'room_service_charge'   => $request->room_service,
                'room_rate'             => $request->room_rate,
                'rst_service_charge'    => $request->rst_service_charge,

            ])->save();

            $setting = $request->key['use_vat_included'];

            SystemSetting::row('use_vat_included')->fill(['value' => $setting])->save();

            cache()->forget('vatSetting');

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('vat.index')->with('message', 'Vat update Successfull');
    }
}
