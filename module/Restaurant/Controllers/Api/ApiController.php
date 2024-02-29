<?php

namespace Module\Restaurant\Controllers\Api;

use Module\Hotel\Models\Guest;
use App\Http\Controllers\Controller;
use Module\Hotel\Models\AccountType;

class ApiController extends Controller
{





    /*
     |--------------------------------------------------------------------------
     | INDEX METHOD
     |--------------------------------------------------------------------------
    */
    public function index()
    {

        try {
            
            $guests   = Guest::searchByField('name')->take(25)->get();

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


    public function accountType()
    {
        try {
            return response()->json([
                'status'    => 1,
                'message'   => 'Success',
                'data'      => AccountType::get(),
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
