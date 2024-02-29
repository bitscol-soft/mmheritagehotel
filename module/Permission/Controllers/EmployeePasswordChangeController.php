<?php

namespace Module\Permission\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCredential;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeePasswordChangeController extends Controller
{
    public function index()
    {
        return 'afksdfj';
        return view('employee.password-changes.index');
    }

    public function changePassword(Request $request)
    {
        return $request->all();

        $request->validate([
            'current_password'      => ['required', new MatchOldPassword],
            'new_password'          => ['required'],
            'new_confirm_password'  => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        UserCredential::updateOrCreate(['user_id' => auth()->id()], ['secrete' => $request->new_password]);

        return 'akfldj';
        return redirect()->back()->withMessage('Password Successfully Changed');

    }
}
