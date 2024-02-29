<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use App\Models\BusinessType;
use Illuminate\Http\Request;
use App\Models\CompanyDetails;
use App\Traits\CheckPermission;
use App\Models\CompanyBankAccount;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    use CheckPermission;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->hasAccess("company.infos.view");     // check permission


        $companies = auth()->id() == 1 ?
            Company::with('group', 'businessType', 'company_details', 'company_bank_account')->latest()->paginate(30) :
            Company::with('group', 'businessType', 'company_details', 'company_bank_account')
                ->whereIn('id', auth()->user()->companies->pluck('id'))
                ->latest()
                ->paginate(30);

        foreach ($companies->where('business_type', Null) as $company) {
            $company->update([
                'business_type' => optional($company->businessType)->name
            ]);
        }

        return view('global.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->hasAccess("company.infos.create");     // check permission

        $data = [];
        $data['business_types'] = BusinessType::all();
        return view('global.companies.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = new Company();
        $company->validate($request);
        try {
            $slug = Str::slug($request->name, '_');
            $logo = $request->file('logo');
            if ($logo) {
                $logoName = $company->logoUpload($logo, $slug);
                $id = $company->storeCompany($request, $logoName);
                if ($request->vat_no) {
                    $company->storeCompanyDetails($request, $id);
                } elseif ($request->account_name) {
                    $company->storeCompanyBankAccount($request, $id);
                }
            } else {
                $logoName = 'default.png';
                $id = $company->storeCompany($request, $logoName);
                if ($request->vat_no) {
                    $company->storeCompanyDetails($request, $id);
                } elseif ($request->account_name) {
                    $company->storeCompanyBankAccount($request, $id);
                }
            }
            $this->uploadHeaderFooter($request, $id);
        } catch (\Exception $e) {
//            return $e->getMessage();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
        return redirect()->back()->with('message', 'Company Added Successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->hasAccess("company.infos.edit");     // check permission


        $data['business_types'] = BusinessType::all();
        $data['company'] = Company::findorFail($id);
        return view('global.companies.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $company = Company::find($id);
        $company->updateValidate($request);

        // try {
            $slug = Str::slug($request->name, '_');
            $logo = $request->file('logo');
            if ($logo) {
                $logoName = $company->logoUpdate($logo, $slug, $id);
                $company->updateCompany($request, $logoName, $id);
                $company->updateCompanyDetails($request, $id);
                $company->updateCompanyBankAccount($request, $id);
            } else {
                $logoName = $company->logo;

                $company->updateCompany($request, $logoName, $id);
                $company->updateCompanyDetails($request, $id);
                $company->updateCompanyBankAccount($request, $id);
            }
            $this->uploadHeaderFooter($request, $id);
        // } catch (\Exception $e) {
        //     if ($e->getCode() == '23000') {
        //         return redirect()->back()->with('error', 'You Can not delete or update because this information is use in another table.')->withInput();
        //     }

        //     return redirect()->back()->with('error', $e->getMessage())->withInput();

        // }
        return redirect()->route('company.index')->with('message', 'Company Update Successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->hasAccess("company.infos.delete");     // check permission
        $company = Company::findOrFail($id);
        try {
            $getUser = User::where('company_id', $id)->first();
            if (!$getUser) {

                $details = CompanyDetails::where('company_id', $id)->first();
                if($details){
                    if($details->header) {
                        @unlink('uploads/company/extra/' . $details->header);
                    }
                    if($details->footer) {
                        @unlink('uploads/company/extra/' . $details->footer);
                    }
                    $details->delete();
                }
                CompanyDetails::where('company_id', $id)->delete();
                CompanyBankAccount::where('company_id', $id)->delete();
                if ($company->logo != 'default.png') {
                    @unlink('uploads/company/' . $company->logo);
                }
                $company->delete();
            } else {
                return redirect()->back()->with('error', 'You Can not delete this Company because this company is use in another table!');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return redirect()->back()->with('error', 'You Can not delete this Company because this company is use in another table!');
            } else {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        return redirect()->back()->with('message', 'Your Company Info Deleted Successful');
    }

    private function uploadHeaderFooter($request, $id)
    {
        $details = CompanyDetails::firstOrCreate(['company_id' => $id]);
        if($request->has('header'))
        {
            $details->header = $this->upload($request->header, Str::slug($request->name . '_header', '_'), $details->header);
        }

        if($request->has('footer'))
        {
            $details->footer = $this->upload($request->footer, Str::slug($request->name . '_footer', '_'), $details->footer);
        }

        if($request->has('organogram'))
        {
            $details->organogram = $this->upload($request->organogram, Str::slug($request->name . '_organogram', '_'), $details->organogram);
        }
        $details->save();
    }

    public function upload($file, $slug, $path)
    {
        $currentDate = Carbon::now()->toDateString();
        $name   = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        if (!file_exists('uploads/company/extra')) {
            mkdir('uploads/company/extra', 0777, true);
        }
        if($path && file_exists('uploads/company/extra/'.$path))
        {
            @unlink('uploads/company/extra/' . $path);
        }
        $file->move('uploads/company/extra',$name);

        return $name;
    }
}
