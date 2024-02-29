<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{


    //--------------------------------------------------------------------------
    //            SYSTEM SETTING METHOD
    //--------------------------------------------------------------------------
    public function index()
    {
        $systemSettings = SystemSetting::get(['key', 'value']);

        $currencies     = Currency::has('currency_conversions')->get();

        return view('system.index', compact('systemSettings', 'currencies'));
    }



    //--------------------------------------------------------------------------
    //            SYSTEM SETTING STORE METHOD
    //--------------------------------------------------------------------------
    public function store(Request $request)
    {

        $request->validate([
            'key.general_store_reference_title'     => 'nullable|max:191',
            'key.out_work_date_picker'              => 'nullable|numeric',
            'key.employee_summary_gross_salary_get' => 'nullable|numeric',
            'key.finger_id_get'                     => 'nullable|numeric',
            'key.employee_list_card_no'             => 'nullable|numeric',
            'key.custom_employee_full_id'           => 'nullable|numeric',
            'key.employee_login_option'             => 'nullable|numeric',
            'key.employee_attendance_chart'         => 'nullable|numeric',
            'key.dashboard'                         => 'nullable|numeric',
        ]);


        foreach ($request->key as $key => $value){

            SystemSetting::where('key', $key)->update([

                'value' => $value

            ]);

        }
        cache()->forget('system_setting');
        cache()->put('system_setting', SystemSetting::get());

        return redirect()->back()->with('message', 'System Setting Update Successful');

    }





    //--------------------------------------------------------------------------
    //            SYSTEM SETTING UPDATE DATA FROM URL METHOD
    //--------------------------------------------------------------------------
    public function updateDataFromUrl(){
        return "Whats Wrong...." . "<br>". "What Are You Doing :(";
    }
}
