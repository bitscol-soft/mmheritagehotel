<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMailable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function username()
    {
        if (is_numeric(request()->email)) {
            return 'employee_full_id';
        }
        return 'email';
    }


    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

    protected function  authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            // Log the user out.
            Auth::logout($request);
            // Return them to the log in form.
            return redirect()->back()->with('error', 'User not found');
        }
        if ($user->status == 2) {
            // Log the user out.
            Auth::logout($request);
            // Return them to the log in form.
            return redirect()->back()->with('error', 'User is deactivated');
        }

        if ($user->status == 1) {
            return redirect(RouteServiceProvider::HOME);
        }

    }

    public function redirectTo()
    {
        $parts = explode('/',request()->session()->previousUrl());

        if(count($parts) >= 4 && $parts[3] == 'em')
            $this->redirectTo = '/em';
    }



    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->removeUserFromCache();


        $path = '/home';
        $parts = explode('/', request()->session()->previousUrl());
        if (count($parts) >= 4 && $parts[3] == 'em') {
            $path = '/em';
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect($path);
    }

    private function removeUserFromCache()
    {
        Cache::clear('logged-in-users-' . auth()->id());
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if ($user) {
            $user->password_reset_token = Hash::make($user->id . uniqid());
            $user->save();

            Mail::to($user->email)->send(new PasswordResetMailable($user));

            return back()->with('message', 'Please Check Your Email For Password Reset Instruction!');
        }

        return back()->with('error', 'Internal Server Error. Please Try Again!');
    }

    public function verifyResetPasswordToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
        ]);

        $data['user'] = User::query()
            ->where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        return view('auth.login', $data);
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'The repeat password does not match.'
        ]);

        $user = User::query()
            ->where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        $user->password = \Hash::make($request->password);
        $user->password_reset_token = null;
        $user->save();

        return redirect()->route('login')->with('message', 'Password Reset Successful. Please Login!');
    }
}
