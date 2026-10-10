<?php

namespace Module\Permission\Controllers;



use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\UserCredential;
use App\Traits\CheckPermission;
use App\Http\Controllers\Controller;
use Module\Permission\Models\Module;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Module\Permission\Models\PermissionFeature;
use Module\Permission\Models\EmployeePermission;

class UserPermissionController extends Controller
{
    use CheckPermission;







    /*
     |--------------------------------------------------------------------------
     | PERMITTED USER LIST
     |--------------------------------------------------------------------------
    */
    public function view_permitted_users(Request $request)
    {

        $this->updateEmployeeId($request);


        if (class_exists('Module\HRM\Models\Employee\Employee')) {
            $users = User::orderByDesc('id')
                ->with('company', 'employee.department', 'employee.designation')
                ->where('status', '>', 0)
                ->where('id', '>', 1)
                ->get();
        } else {
            $users = User::orderByDesc('id')
                ->with('company')
                ->where('status', '>', 0)
                ->where('id', '>', 1)
                ->get();
        }


        return view('users.index', compact('users'));
    }







    /*
     |--------------------------------------------------------------------------
     | USER CREATE
     |--------------------------------------------------------------------------
    */
    public function createUser(Request $request)
    {

        $companies = Company::userCompanies();

        return view('users.create', compact('companies'));
    }







    /*
     |--------------------------------------------------------------------------
     | STORE CREATE
     |--------------------------------------------------------------------------
    */
    public function storeUser(Request $request)
    {
        $request->validate([

            'name'              => 'required',
            'email'             => 'required|unique:users,email',
            'password'          => 'required|min:5',
            'confirm_password'  => 'required|same:password',
        ]);



        try {


            $user = User::create([

                'name'          => $request->name,
                'email'         => $request->email,
                'phone_number'  => $request->mobile_number,
                'password'      => Hash::make($request->password),
                'company_id'    => auth()->user()->company_id
            ]);


            // set visible password into user_credentials table
            UserCredential::updateOrCreate(['user_id' => $user->id], ['secrete' => $request->password]);
        } catch (\Exception $ex) {

            return redirect()->back()->withInput()->withError($ex->getMessage());
        }



        return redirect()->route('permitted.users')->withMessage('User Successfully Created');
    }









    /*
     |--------------------------------------------------------------------------
     | ADD EMPLOYEE FULL ID IN USER TABLE
     |--------------------------------------------------------------------------
    */
    public function updateEmployeeId($request)
    {
        if ($request->filled('update_type')) {

            $users = User::active()
                ->whereNotNull('employee_id')
                ->whereNull('employee_full_id')
                ->with('employee:id,employee_full_id')
                ->get();

            foreach ($users ?? [] as $user) {

                $user->update(['employee_full_id' => optional($user->employee)->employee_full_id]);
            }
        }
    }











    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD HELP FOR
     |--------------------------------------------------------------------------
    */
    public function index($id)
    {
        $this->hasAccess("permission.accesses.create");     // check permission





        $data['user']               = $user = User::where('employee_id', $id)->where('status', 1)->first();
        $data['modules']            = Module::with('submodules.parent_permissions.permissions')->get();
        $data['companies']          = Company::pluck('name', 'id');



        Schema::hasTable('departments')
            ? $data['departments']        = \Module\HRM\Models\Department::pluck('name', 'id')
            : $data['departments'] = [];


            Schema::hasTable('designations')
            ? $data['designations']        = \Module\HRM\Models\Designation::pluck('name', 'id')
            : $data['designations'] = [];






        $data['isPermitted']        = $user->permissions()->pluck('slug')->toArray();
        $data['hasCompanies']       = $user->companies()->pluck('name')->toArray();
        Schema::hasTable('departments')
            ?   $data['hasDepartments']     = $user->departments()->pluck('name')->toArray()
            :   $data['hasDepartments']     = [];

            Schema::hasTable('designations')
            ?   $data['hasDesignations']     = $user->designations()->pluck('name')->toArray()
            :   $data['hasDesignations']     = [];

        $data['hasFeatures']        = PermissionFeature::where('status', 1)->pluck('name')->toArray();




        (class_exists(\Module\HRM\Models\Employee\Employee::class) && Schema::hasTable('employees'))
            ? $data['employee_ids'] = \Module\HRM\Models\Employee\Employee::whereDoesntHave('user')
            ->select('employee_full_id', 'email', 'company_id', 'department_id', 'designation_id', 'id', 'name')
            ->with('department:name,id', 'designation:name,id')
            ->get()
            : $data['employee_ids'] = [];


            (class_exists(\Module\HRM\Models\Employee\Employee::class) && Schema::hasTable('employees'))
            ? $data['existing_employee']  = \Module\HRM\Models\Employee\Employee::whereHas('user', function ($q) {
                $q->where('status', 1);
            })
            ->get(['employee_full_id', 'id', 'name'])
            : $data['existing_employee']  = [];




        return view('access.create', $data);
    }















    /*
     |--------------------------------------------------------------------------
     | CREATE/PERMISSION ACCESS
     |--------------------------------------------------------------------------
    */
    public function create()
    {

        $this->hasAccess("permission.accesses.create");




        $data['modules']                = Module::where('name', '!=', 'Employee Permission')->with('submodules.parent_permissions.permissions')->active()->get();
        $data['companies']              = Company::pluck('name', 'id');

        $data['departments']            = [];

        if (Schema::hasTable('departments') && class_exists('\Module\HRM\Models\Department')) {
            $data['departments']        = \Module\HRM\Models\Department::pluck('name', 'id');
        }


        $data['designations']        = [];
        if (Schema::hasTable('designations') && class_exists('\Module\HRM\Models\Designation')) {
            $data['designations']        = \Module\HRM\Models\Designation::pluck('name', 'id');
        }


        $data['hasFeatures']            = PermissionFeature::where('status', 1)->pluck('name')->toArray();


        $data['employee_ids']           = [];
        $data['existing_employee']      = [];

        if (Schema::hasTable('employees') && class_exists('\Module\HRM\Models\Employee\Employee')) {
            $data['employee_ids']       = \Module\HRM\Models\Employee\Employee::whereDoesntHave('user')
                ->select('employee_full_id', 'email', 'company_id', 'department_id', 'designation_id', 'id', 'name')
                ->with('department:name,id', 'designation:name,id')
                ->get();


            $data['existing_employee']  = \Module\HRM\Models\Employee\Employee::whereHas('user', function ($q) {
                $q->where('status', 1)->whereNotNull('employee_id');
            })->get(['employee_full_id', 'id', 'name']);
        }

        return view('access.create', $data);
    }














    /*
     |--------------------------------------------------------------------------
     | STORE/GIVE PERMISSION ACCESS
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        // validation
        $request->validate([
            'employee_id'    => 'required',
            'email'          => 'required|unique:users',
            'password'       => 'required|min:6',
        ]);




        try {

            $user = User::where('employee_id', '=', $request->employee_id)->first();


            if ($user != null) {

                return redirect()->back()->with('error', 'This employee already has a permission');
            } else {

                $request->validate([

                    'email'             => 'required|unique:users,email'
                ]);

                $user_created = User::create([

                    'name'              => $request->employee_name,
                    'employee_id'       => $request->employee_id,
                    'employee_full_id'  => $request->employee_full_id,
                    'company_id'        => $request->company_id,
                    'email'             => $request->email,
                    'password'          => Hash::make($request->password)
                ]);


                $hasFeatures     = PermissionFeature::where('status', 1)->pluck('name')->toArray();



                if ($user_created) {

                    // set companies permission
                    if (in_array('Company', $hasFeatures)) {
                        $user_created->companies()->sync($request->companies);
                    }


                    // set departments permission
                    if (in_array('Department', $hasFeatures)) {
                        if (Schema::hasTable('departments')) {
                            $user_created->departments()->sync($request->departments);
                        }
                    }


                    // set designations permission
                    if (in_array('Designation', $hasFeatures)) {
                        if (Schema::hasTable('designations')) {
                            $user_created->designations()->sync($request->designations);
                        }
                    }



                    // set user permission
                    $user_created->permissions()->sync($request->permissions);



                    // set visible password into user_credentials table
                    UserCredential::updateOrCreate(['user_id' => $user_created->id], ['secrete' => $request->password]);
                }
            }

            return back()->with('message', 'Permission create success');
        } catch (Exception $ex) {


            return back()->with('error', $ex->getMessage());
        }
    }















    /*
     |--------------------------------------------------------------------------
     | EDIT USER PERMISSION
     |--------------------------------------------------------------------------
    */
    public function edit($id)
    {

        $this->hasAccess("permission.accesses.edit");     // check permission

        $user                 = User::findOrFail($id);
        $data['user']         = $user;
        $data['modules']      = Module::where('name', '!=', 'Employee Permission')->with('submodules.parent_permissions.permissions')->active()->get();
        $data['companies']    = Company::pluck('name', 'id');

        $data['departments']            = [];
        if (Schema::hasTable('departments')) {
            $data['departments']        = \Module\HRM\Models\Department::pluck('name', 'id');
        }


        $data['designations']        = [];
        if (Schema::hasTable('designations')) {
            $data['designations']        = \Module\HRM\Models\Designation::pluck('name', 'id');
        }


        $data['hasFeatures']            = PermissionFeature::where('status', 1)->pluck('name')->toArray();


        $data['employee_ids']           = [];
        $data['existing_employee']      = [];

        if (Schema::hasTable('employees')) {
            $data['employee_ids']       = \Module\HRM\Models\Employee\Employee::whereDoesntHave('user')
                ->select('employee_full_id', 'email', 'company_id', 'department_id', 'designation_id', 'id', 'name')
                ->with('department:name,id', 'designation:name,id')
                ->get();


            $data['existing_employee']  = \Module\HRM\Models\Employee\Employee::whereHas('user', function ($q) {
                $q->where('status', 1)->whereNotNull('employee_id');
            })->get(['employee_full_id', 'id', 'name']);
        }



        $data['isPermitted']            = $user->permissions()->pluck('slug')->toArray();
        $data['hasCompanies']           = $user->companies()->pluck('name')->toArray();

        Schema::hasTable('departments')
            ? $data['hasDepartments']        = $user->departments()->pluck('name')->toArray()
            : $data['hasDepartments'] = [];

            Schema::hasTable('designations')
            ? $data['hasDesignations']        = $user->designations()->pluck('name')->toArray()
            : $data['hasDesignations'] = [];


        $data['hasFeatures']            = PermissionFeature::where('status', 1)->pluck('name')->toArray();


        return view('access.edit', $data);
    }















    /*
     |--------------------------------------------------------------------------
     | UPDATE USER PERMISSION
     |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {

        try {

            $user_created = User::findOrFail($id);


            $hasFeatures  = PermissionFeature::where('status', 1)->pluck('name')->toArray();

            // update companies permission
            if (in_array('Company', $hasFeatures)) {
                $user_created->companies()->sync($request->companies);
            }


            // update departments permission
            if (in_array('Department', $hasFeatures)) {
                if (Schema::hasTable('departments')) {
                    $user_created->departments()->sync($request->departments);
                }
            }


            // set designations permission
            if (in_array('Designation', $hasFeatures)) {
                if (Schema::hasTable('designations')) {
                    $user_created->designations()->sync($request->designations);
                }
            }


            // update user permission
            $user_created->permissions()->sync($request->permissions);





            // remove permission sessions for this user
            if (auth()->id() == $id) {
                session()->forget('slugs');
            }
        } catch (\Exception $ex) {


            return redirect()->back()->withError($ex->getMessage());
        }

        return redirect()->back()->withMessage('Permission Successfully Updated');
    }















    /*
     |--------------------------------------------------------------------------
     | GET EMPLOYEE VIA AJAX
     |--------------------------------------------------------------------------
    */
    public function employee_list(Request $request)
    {
        // the previous ternary called HRM in BOTH branches -> fatal without the module
        if (! class_exists(\Module\HRM\Models\Employee\Employee::class) || ! Schema::hasTable('employees')) {
            return response()->json(null);
        }

        $employees_info = \Module\HRM\Models\Employee\Employee::with(['company', 'department', 'designation', 'bank_information'])
            ->where('id', $request->id)
            ->orWhere('employee_full_id', $request->id)
            ->where('status', 1)->first();

        return response()->json($employees_info);
    }















    /*
     |--------------------------------------------------------------------------
     | PERMITTED EMPLOYEE LSIT VIA AJAX
     |--------------------------------------------------------------------------
    */
    public function permittedEmployeeList()
    {
        // HRM must exist AND provide its models, otherwise return an empty list
        if (! class_exists(\Module\HRM\Models\Employee\Employee::class) || ! Schema::hasTable('employees')) {
            return response()->json([]);
        }

        $employees = \Module\HRM\Models\Employee\Employee::whereStatus(1)
            ->whereIn('company_id', Company::userCompanyId())
            ->orderBy('name')
            ->select('employee_full_id', 'name')
            ->get();

        return response()->json($employees);
    }













    /*
     |--------------------------------------------------------------------------
     | EMPLOYEE PROFILE PERMISSION ACCESS
     |--------------------------------------------------------------------------
    */
    public function employeePermission(Request $request)
    {
        $this->hasAccess("permission.accesses.create");     // check permission

        if (Schema::hasTable('employee_permissions')) {
            $data['isEmployeePermitted']    = EmployeePermission::with('permission')->get()->pluck('permission.slug')->toArray();
        } else {
            $data['isEmployeePermitted']    = [];
        }

        $data['modules']                = Module::where('name', 'Employee Permission')->with('submodules.parent_permissions.permissions')->active()->get();

        return view('access.employee-permission', $data);
    }















    /*
     |--------------------------------------------------------------------------
     | EMPLOYEE PROFILE PERMISSION ACCESS STORE
     |--------------------------------------------------------------------------
    */
    public function employeePermissionStore(Request $request)
    {
        $this->hasAccess("permission.accesses.create");     // check permission

        try {

            EmployeePermission::whereNotIn('permission_id', $request->employee_permissions ?? [])->delete();

            $old_employee_permissions = EmployeePermission::pluck('permission_id')->toArray() ?? [];

            $new_items = array_diff(array_filter($request->employee_permissions), $old_employee_permissions);
            $employee_permissions = [];

            foreach ($new_items as $key => $new_item) {
                $employee_permissions[] = [
                    'permission_id' => $new_item
                ];
            }

            if (count($employee_permissions)) {
                EmployeePermission::insert($employee_permissions);
            }

            return back()->with('message', 'Permission assign successfully');
        } catch (Exception $ex) {

            return back()->with('error', $ex->getMessage());
        }
    }
}
