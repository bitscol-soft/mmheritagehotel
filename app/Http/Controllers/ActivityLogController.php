<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Module\HRM\Models\Employee\Employee;

class ActivityLogController extends Controller
{






    //--------------------------------------------------------------------------
    //                          ACTIVITY LOG METHOD
    //--------------------------------------------------------------------------
    public function index(Request $request){
        $from = $request->from ? date('Y-m-d H:i:s', strtotime($request->from)) : date('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->addDays(1);
        $to = $request->from ? date('Y-m-d H:i:s', strtotime($to)) : date('Y-m-d H:i:s');

        $data['users'] = User::active()->get();
        $data['logs'] = ActivityLog::with('causer')
                        ->when($request->filled('user_id'), function($q) use($request){
                            $q->where('causer_type', 'App\Models\User')->where('causer_id', $request->user_id);
                        })
                        ->when($request->filled('log_name'), function($q) use($request){
                            $q->where('log_name', $request->log_name);
                        })
                        ->when($request->filled('from'), function($q) use($request, $from, $to){
                            $q->where('created_at', '>=', $from)
                              ->where('created_at', '<=', $to);
                        })
                        ->orderBy('id', 'desc')->paginate(40);
        $data['log_names'] = ActivityLog::count() > 0 ? ActivityLog::groupBy('log_name')->orderBy('id', 'desc')->get() : [];
        return view("activity-log.index", $data);
    }
}
