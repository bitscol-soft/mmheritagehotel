<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Module\HRM\Models\AttendanceDeviceApi\Device;
use Module\HRM\Models\AttendanceDeviceApi\Log;
use Illuminate\Support\Str;

class LogController extends Controller
{

    public function push(Request $request)
    {
        $message = '';
        if($request->has('push_key')){
            if(env('PUSH_KEY') == $request->post('push_key')){

                if($request->has('identifier') && $request->has('date')) {
                    $device_identifier = $request->post('identifier');
                    $user_id = $request->post('user');

                    try {
                        $date_time  = Carbon::make($request->post('date'))->toDateTimeString();
                        $device     = Device::where('identifier', $device_identifier)->first();

                        if(!empty($device)) {

                            if (Log::where(['device_id' => $device->id, 'person_id' => $user_id, 'log_time' => $date_time])->count() <= 0) {

                                Log::insert([
                                    'uid'       => $this->get_uid(),
                                    'device_id' => $device->id,
                                    'person_id' => $user_id,
                                    'log_time'  => $date_time,
                                    'date'      => fdate($date_time, 'Y-m-d'),
                                ]);

                                $message = 'accapted';
                                return response("accapted");
                            }
			    return response('');

                        }  else {
                            $message = "Device ".$device_identifier." not registered on server";
                            echo "Device ".$device_identifier." not registered on server";
                        }
                    } catch (\Exception $ex) {
                        $message = $ex->getMessage();
                        echo $message;
                    }
                } else {
                    $message = 'Required data missing';
                    echo "Required data missing";
                }

            } else {
                $message = 'Push key error';
                echo "Push key error";
            }
        } else {
            $message = 'Push key missing';
            echo "Push key missing";
        }
        return response($message);

    }


    private function get_uid()
    {
        do {
            $uid = Str::uuid();
        } while (! empty(Log::where('uid', $uid)->count() >= 1));

        return $uid;
    }
}
