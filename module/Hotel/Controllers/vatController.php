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
        $data['vat'] = Vat::first();
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

            $vat = Vat::find($id);
            $vat->update([

                'hotel_vat'             => $request->hotel_vat,
                'resturent_vat'         => $request->resturent_vat,
                'bar_vat'               => $request->bar_vat,
                'vat_number'            => $request->vat_number,
                'room_service_charge'   => $request->room_service,
                'room_rate'             => $request->room_rate,
                'rst_service_charge'    => $request->rst_service_charge,

            ]);

            $systemSetting = SystemSetting::where('key', 'use_vat_included')->first();

            $setting = $request->key['use_vat_included'];

            $systemSetting->update([

                'value' => $setting,

            ]);

            cache()->forget('vatSetting');

        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
        return redirect()->route('vat.index')->with('message', 'Vat update Successfull');
    }
}
