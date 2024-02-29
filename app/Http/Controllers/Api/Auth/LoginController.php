<?php


namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController
{





    /**
     * --------------------------------------------------------
     * LOGIN METHOD
     * --------------------------------------------------------
     */
    public function login(Request $request)
    {

        
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required|string'
        ]);

        if ($validator->fails()) {

            return response()->json([

                'message'   => $validator->errors()->first(),
                'data'      => "Validation Error",
                'status'    => 0,
            ]);
        }


        $user = User::where('email', $request->email)->first();

        // check user is exist or not
        if (!$user) {

            return response()->json([

                'message'   => "User Not Found !",
                'data'      => "Error",
                'status'    => 0,
            ]);
        }

        // check user is exist or not
        if (!Hash::check($request->password, $user->password)) {

            return response()->json([

                'message'   => "Password Not Match !",
                'data'      => "Error",
                'status'    => 0,
            ]);
        }



        // check user is exist or not
        if ($user->status == 0) {

            return response()->json([

                'data'      => "User is Deactivated",
                'message'   => "Error",
                'status'    => 0,
            ]);
        }

        $user->device_token = $request->device_token;
        $user->api_token    = $user->id .'t'. Str::random(70);
        $user->save();


        // create bearer token for authentication
        $data                 = $user;
        $data['access_token'] = $user->createToken('AppToken')->plainTextToken;

        return response()->json([

            'data'      => $data,
            'message'   => "Success",
            'status'    => 1,
        ]);

    }


    public function logout(Request $request)
    {


        try {

            auth()->user()->tokens()->delete();

            return response([

                'data'      => "Logged Out Successfully.",
                'status'    => 1,
                'message'   => 'Success'
            ]);

        } catch (\Exception $ex) {

            return response([

                'data'      => "User not login or Server Error.",
                'status'    => 0,
                'message'   => "Error"
            ]);
        }
    }
}
