<?php

namespace App\Models;

use Module\HRM\Models\Employee\Employee;
use App\Models\Gs\Customer;
use Module\GeneralStore\Models\GoodsRequisition;
use App\Models\Gs\ItemUnit;
use Module\GeneralStore\Models\Purchase;
use Module\GeneralStore\Models\PurchaseReceive;
use Module\HRM\Models\Attendance\Shift;
use App\Models\Salary\EligibleSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Module\Production\Models\Factory;
use Illuminate\Support\Facades\Schema;

class Company extends Model
{
    protected $fillable = [
        'group_id', 'name', 'business_type_id', 'code', 'short_name', 'day_off', 'head_office', 'factory', 'contact_name',
        'position',  'phone_number', 'fax', 'email', 'country', 'top_text', 'logo', 'latitude', 'longitude', 'business_type'
    ];


    public function scopeActive($query)
    {
        if (Schema::hasColumn('company', 'status')) {
            return $query->where('status', 1);
        }

        return $query;
    }

    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function ($model) {
                $model->fill([
                    'group_id' => optional(optional(optional(auth()->user())->company)->group)->id ?? optional(Group::first())->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            });
            static::updating(function ($model) {
                $model->fill([
                    'updated_at' => Carbon::now(),
                ]);
            });
        }
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function businessType()
    {
        return $this->belongsTo(BusinessType::class);
    }
    public function company_details()
    {
        return $this->hasOne(CompanyDetails::class);
    }
    public function company_bank_account()
    {
        return $this->hasMany(CompanyBankAccount::class);
    }

    public function eligible_duration()
    {
        return $this->hasOne(EligibleSetting::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function factory_name()
    {
        return $this->hasMany(Factory::class);
    }

    public function validate($request)
    {
        $request->validate([
            'name' => 'required',
//            'business_type_id' => 'required',
        ]);
    }

    public function logoUpload($logo, $slug)
    {
        $currentDate = Carbon::now()->toDateString();
        $logoName   = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
        if (!file_exists('uploads/company')) {
            mkdir('uploads/company', 0777, true);
        }

        $logo->move('uploads/company', $logoName);
        return $logoName;
    }


    public function storeCompany($request, $logoName)
    {
        $companyId = $this->create([

            'group_id'          => optional(optional(optional(auth()->user())->company)->group)->id ?? optional(Group::first())->id,
            'name'              => $request->name,
            'business_type'     => $request->business_type,
            'business_type_id'  => $request->business_type_id,
            'code'              => $request->code,
            'short_name'        => $request->short_name,
            'day_off'           => $request->day_off,
            'head_office'       => $request->head_office,
            'factory'           => $request->factory,
            'contact_name'      => $request->contact_name,
            'position'          => $request->position,
            'phone_number'      => $request->phone_number,
            'fax'               => $request->fax,
            'email'             => $request->email,
            'country'           => $request->country,
            'top_text'          => $request->top_text,
            'logo'              => $logoName,
            // 'latitude'          => $request->latitude,
            // 'longitude'         => $request->longitude,
        ])->id;

        return $companyId;
    }

    public function storeCompanyDetails($request, $companyId)
    {
        CompanyDetails::create([

            'company_id'        => $companyId,
            'vat_no'            => $request->vat_no,
            'facsimile_number'  => $request->facsimile_number,
            'bonded_license'    => $request->bonded_license,
            'membership_number' => $request->membership_number,
            'bkmea_reg_no'      => $request->bkmea_reg_no,
            'import_reg_certi'  => $request->import_reg_certi,
            'export_reg_certi'  => $request->export_reg_certi,
            'epb_reg_no'        => $request->epb_reg_no,
        ]);
    }

    public function storeCompanyBankAccount($request, $companyId)
    {
        $account_name = $request->account_name;
        if ($account_name != NULL) {
            foreach ($account_name as $key => $value) {
                CompanyBankAccount::create([
                    'company_id'        => $companyId,
                    'account_name'      => $request->account_name[$key],
                    'account_number'    => $request->account_number[$key],
                    'bank_name'         => $request->bank_name[$key],
                    'branch'            => $request->branch[$key],
                    'swift_code'        => $request->swift_code[$key],
                ]);
            }
        }
    }


    public function updateValidate($request)
    {
        $request->validate([
            'name' => 'required',
//            'business_type_id' => 'required',
        ]);
    }



    public function logoUpdate($logo, $slug, $id)
    {
        $currentDate = Carbon::now()->toDateString();
        $logoName   = $slug . '_' . $currentDate . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
        if (!file_exists('uploads/company')) {
            mkdir('uploads/company', 0777, true);
        }
        $getLogo = $this->where('id', $id)->select('logo')->firstOrFail();
        if ($getLogo->logo != 'default.png') {
@unlink('uploads/company/' . $getLogo->logo);
        }
        $logo->move('uploads/company', $logoName);
        return $logoName;
    }


    public function updateCompany($request, $logoName, $id)
    {

        $this->update([
            'group_id'          => optional(optional(optional(auth()->user())->company)->group)->id ?? optional(Group::first())->id,
            'name'              => $request->name,
            'business_type'     => $request->business_type,
            'business_type_id'  => $request->business_type_id,
            'code'              => $request->code,
            'short_name'        => $request->short_name,
            'day_off'           => $request->day_off,
            'head_office'       => $request->head_office,
            'factory'           => $request->factory,
            'contact_name'      => $request->contact_name,
            'position'          => $request->position,
            'phone_number'      => $request->phone_number,
            'fax'               => $request->fax,
            'email'             => $request->email,
            'country'           => $request->country,
            'top_text'          => $request->top_text,
            'logo'              => $logoName,
            // 'latitude'          => $request->latitude,
            // 'longitude'         => $request->longitude,
        ]);
    }

    public function updateCompanyDetails($request, $companyId)
    {
        $getCompanyDetail = CompanyDetails::where('company_id', $companyId)->first();
        if ($getCompanyDetail) {
            CompanyDetails::where('id', $getCompanyDetail->id)->update([
                'company_id'        => $companyId,
                'vat_no'            => $request->vat_no,
                'facsimile_number'  => $request->facsimile_number,
                'bonded_license'    => $request->bonded_license,
                'membership_number' => $request->membership_number,
                'bkmea_reg_no'      => $request->bkmea_reg_no,
                'import_reg_certi'  => $request->import_reg_certi,
                'export_reg_certi'  => $request->export_reg_certi,
                'epb_reg_no'        => $request->epb_reg_no,
            ]);

        } else {
            CompanyDetails::create([
                'company_id'        => $companyId,
                'vat_no'            => $request->vat_no,
                'facsimile_number'  => $request->facsimile_number,
                'bonded_license'    => $request->bonded_license,
                'membership_number' => $request->membership_number,
                'bkmea_reg_no'      => $request->bkmea_reg_no,
                'import_reg_certi'  => $request->import_reg_certi,
                'export_reg_certi'  => $request->export_reg_certi,
                'epb_reg_no'        => $request->epb_reg_no,
            ]);
        }
    }

    public function updateCompanyBankAccount($request, $companyId)
    {

        $existing_bank_accounts = CompanyBankAccount::where('company_id', $companyId)->get();


        foreach ($existing_bank_accounts as $key => $account) {
            if (!in_array($account->id, $request->companyBankId)) {
                $account->delete();
            }
        }

        $account_name = $request->account_name;

        if ($request->new_account_name) {
            foreach ($request->new_account_name as $key => $value) {

                CompanyBankAccount::create([
                    'company_id'        => $companyId,
                    'account_name'      => $request->new_account_name[$key],
                    'account_number'    => $request->new_account_number[$key],
                    'bank_name'         => $request->new_bank_name[$key],
                    'branch'            => $request->new_branch[$key],
                    'swift_code'        => $request->new_swift_code[$key],
                ]);
            }

        } elseif ($account_name) {
            $getBankAccount = CompanyBankAccount::where('company_id', $companyId)->first();
            if ($getBankAccount) {
                foreach ($account_name as $key => $value) {

                    CompanyBankAccount::where('id', $request->companyBankId[$key])->update([
                        'company_id'        => $companyId,
                        'account_name'      => $request->account_name[$key],
                        'account_number'    => $request->account_number[$key],
                        'bank_name'         => $request->bank_name[$key],
                        'branch'            => $request->branch[$key],
                        'swift_code'        => $request->swift_code[$key],
                    ]);
                }
            }
        }
    }


    public function item_units()
    {
        return $this->hasMany(ItemUnit::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function purchase_receive()
    {
        return $this->hasMany(PurchaseReceive::class);
    }

    public function good_requisitions()
    {
        return $this->hasMany(GoodsRequisition::class);
    }

    public function shift()
    {
        return $this->hasOne(Shift::class);
    }


    public static function userCompanies()
    {
        return auth()->id() == 1 ? Company::pluck('name', 'id') : (optional(optional(Auth::user())->companies)->pluck('name', 'id') ?? []);
    }

    public static function userCompanyId()
    {
        return auth()->id() == 1 ? Company::pluck('id') : (optional(optional(Auth::user())->companies)->pluck('id') ?? []);
    }

    public function employee_departments()
    {
        return $this->hasMany(Employee::class)->distinct()->select('department_id');
    }

    public static function CompanyByShort()
    {
        return auth()->id() == 1 ? Company::pluck('short_name', 'id') : (optional(optional(Auth::user())->companies)->pluck('short_name', 'id') ?? []);
    }

}
