<?php

namespace Module\Restaurant\Controllers\Api;

use Illuminate\Http\Request;
use Module\Hotel\Models\Guest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GuestController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        try {
            $guests   = Guest::queryLike('name')->orderBy('id','DESC')->paginate(30);

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $guests,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }


    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function getGuestDetails($id)
    {

        try {
            $guests   = Guest::where('id', $id)->first();

            if ($guests == null) {
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Error',
                    'data'      => 'Guest Id Not Found!',
                ]);
            }

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $guests,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }



    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'             => 'required',
            'email'            => 'nullable',
            'phone_no'         => 'nullable',
            'gender'           => 'nullable',
        ]);

        if ($validator->fails()) {

            return response()->json([

                'data'      => $validator->errors()->first(),
                'message'   => "Validation Error",
                'status'    => 0,
            ]);
        }

        try {

            $guest   = Guest::create($request->all());

            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => $guest,
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'status'    => 0,
                'message'   => 'Error',
                'data'      => $th->getMessage(),
            ]);

        }
    }




}
