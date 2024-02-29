<?php

namespace App\Http\Controllers;

use App\Models\UserCredential;
use Module\HRM\Models\Employee\Employee;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // password change page
    public function changePassword()
    {
        return view('users.change_password');
    }

    // admin change any users password
    public function AdminChangePassword($id)
    {
        return view('users.change_password_by_admin', ['user' => User::find($id)]);
    }


    // update users password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
            // UserCredential::updateOrCreate(['user_id' => auth()->id()], ['credential' => $request->new_password]);
            UserCredential::updateOrCreate(['user_id' => auth()->id()], ['secrete' => $request->new_password]);

            return redirect()->back()->with('message', 'Password change successfully');
        } catch (Exception $ex) {
            // return $ex->getMessage();
            return redirect()->back()->with('error', 'Some error please check');
        }
    }

    // update users password by admin
    public function AdminUpdatePassword(Request $request)
    {

        $request->validate([
//            'email'             => ['required'],
            'new_password'      => ['required'],
            'confirm_password'  => ['same:new_password'],
        ]);

        try {
            User::find($request->id)
                ->update([
                'password' => Hash::make($request->new_password)
            ]);
            UserCredential::updateOrCreate(['user_id' => $request->id], ['secrete' => $request->new_password]);
            return redirect()->back()->with('message', 'Password change successfully');
        } catch (Exception $ex) {
            return $ex->getMessage();
            return redirect()->back()->with('error', 'Some error please check');
        }
    }

    public function addUserFromEmployee()
    {
        $employees = Employee::whereDoesntHave('user')->whereNotNull('email')->take(50)->get()
        ->map(function($employee) {
            return [
                'name'              => $employee->name,
                'employee_id'       => $employee->id,
                'company_id'        => $employee->company_id,
                'email'             => $employee->email ?? '',
                'employee_full_id'  => $employee->employee_full_id,
                'password'          => Hash::make(12345678),
                'status'            => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        })->toArray();

        User::insert($employees);

        return count($employees) . ' user created successfully';
    }
}
