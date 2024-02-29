<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Traits\FileSaver;
use Illuminate\Http\Request;
use App\Models\IdCardSetting;
use App\Traits\CheckPermission;

class IdCardSettingController extends Controller
{
    use CheckPermission, FileSaver;


    //--------------------------------------------------------------------------
    //                          INDEX METHOD
    //--------------------------------------------------------------------------
    public function index()
    {
        $this->hasAccess("id.card.settings.index");     // check permission

        $idCardSettings = IdCardSetting::with('company')->get();

        return view('global.id-card-settings.index', compact('idCardSettings'));
    }



    //--------------------------------------------------------------------------
    //                          CREATE METHOD
    //--------------------------------------------------------------------------
    public function create()
    {
        $companies = Company::userCompanies();

        return view('global.id-card-settings.create', compact('companies'));
    }



    //--------------------------------------------------------------------------
    //                          STORE METHOD
    //--------------------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'company_id'    => 'required|unique:id_card_settings,company_id',
            'mobile'        => 'required',
            'address'       => 'required',
            'logo'          => 'required',
            'signature'     => 'required',
        ]);

        try {

            $created = IdCardSetting::create($request->except(['logo', 'signature', 'group_logo']));

            $this->upload_file($request->logo, $created, 'logo', 'uploads/id-card-settings') ;
            $this->upload_file($request->signature, $created, 'signature', 'uploads/id-card-settings');
            $this->upload_file($request->group_logo, $created, 'group_logo', 'uploads/id-card-settings');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
        return redirect()->back()->with('message', 'Setting Created Successfuly');
    }



    //--------------------------------------------------------------------------
    //                          SHOW METHOD
    //--------------------------------------------------------------------------
    public function show(IdCardSetting $idCardSetting)
    {
        //
    }



    //--------------------------------------------------------------------------
    //                          EDIT METHOD
    //--------------------------------------------------------------------------
    public function edit(IdCardSetting $idCardSetting)
    {
        return view('global.id-card-settings.edit', compact('idCardSetting'));
    }



    //--------------------------------------------------------------------------
    //                          UPDATE METHOD
    //--------------------------------------------------------------------------
    public function update(Request $request, IdCardSetting $idCardSetting)
    {
        $request->validate([
            'mobile'        => 'required',
            'address'       => 'required',
        ]);

        try {

            $idCardSetting->update($request->except(['logo', 'signature', 'group_logo']));

            $this->upload_file($request->logo, $idCardSetting, 'logo', 'uploads/id-card-settings');
            $this->upload_file($request->signature, $idCardSetting, 'signature', 'uploads/id-card-settings');
            $this->upload_file($request->group_logo, $idCardSetting, 'group_logo', 'uploads/id-card-settings');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->back()->with('message', 'Setting Edited Successfuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IdCardSetting  $idCardSetting
     * @return \Illuminate\Http\Response
     */

    //--------------------------------------------------------------------------
    //                          DELETE METHOD
    //--------------------------------------------------------------------------
    public function destroy(IdCardSetting $idCardSetting)
    {
        //
    }
}
