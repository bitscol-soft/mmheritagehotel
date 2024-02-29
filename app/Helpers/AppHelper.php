<?php



// check authenticate use is system admin or not

use Carbon\Carbon;
use App\Models\Group;
use Module\Hotel\Models\Vat;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Module\Hotel\Models\AccountType;

function isSystemAdmin()
{
    return auth()->id() == 1;
}




// check footer panel show or not
function isShowFooter()
{
    return  !request()->is('hrm/payroll/master-salary/*')
        &&  !request()->is('hrm/payroll/bank-salary/*')
        &&  !request()->is('hrm/payroll/cash-salary/*')
        &&  !request()->is('hrm/payroll/master-salary-without-payslip/*')
        &&  !request()->is('hrm/payroll/master-salary-with-payslip/*');
}




// get group info
function getGroup()
{
    return Cache::remember('group', $oneDay = 86400, function () {
        return Group::first();
    });
}


// get group info
function getSaleDate($date = null)
{
    return today_from_system();
    if ($date) {
        cache()->forget('sale_date');
    }
    return Cache::remember('sale_date', $oneDay = 86400, function () use ($date) {
        return $date;
    });
}



function hasBranchingSystem()
{
    return (bool) Cache::rememberForever('company-branch', function () {

        return optional(SystemSetting::where('key', 'company_branch')->first())->value;
    });
}



function group()
{
    return Cache::rememberForever('group', function () {

        return Group::first()->name;
    });
}


function system_setting()
{
    return Cache::rememberForever('system_setting', function () {
        return SystemSetting::get();
    });
}





function setting($key)
{
    return optional(system_setting()->where('key', $key)->first())->value;
}







function setTableColumns($table, $columns = [])
{
    $datas = [];
    foreach (collect($columns) as $item) {
        $datas[] = [
            'name'          => $item,
            'is_visible'    => 1,
        ];
    }

    Session::put($table, $datas);
}

function getTableColumns($table)
{
    $datas = Session::get($table);
    return collect($datas);
}


function isVisibleColumn($table, $column)
{
    return optional(getTableColumns($table)->where('name', $column)->first())['is_visible'] ?? 0;
}

// get fav icon
function getFavIcon()
{
    if (file_exists(optional(getGroup())->fav_icon)) {
        return $fav_icon = asset(optional(getGroup())->fav_icon);
    }

    return '/icon.png';
}



// check user login in local
function isApplicationInLocal()
{
    try {

        return env('APP_PRODUCTION_TYPE') == 'local';
    } catch (\Exception $ex) {

        return false;
    }
}



// get
function getGoogleDriveUrl($path)
{
    return 'https://drive.google.com/uc?id=' . $path . '&export=media';
}



// get
function drive_asset($path)
{
    return 'https://drive.google.com/uc?id=' . $path . '&export=media';
}


// delete file from google drive using path
function deleteGoogleDriveFile($file_path)
{

    try {

        if (!isApplicationInLocal()) {


            Storage::cloud()->delete($file_path);
        }
    } catch (\Exception $ex) {
    }
}









function getBloodGroups()
{
    return ['A-', 'A+', 'B-', 'B+', 'AB-', 'AB+', 'O-', 'O+'];
}





function getAmountTotalOfXPercent($total_amount, $percent)
{
    return ($total_amount * $percent) / 100;
}


function getPercentOfXAmount($total_amount, $discount_amount)
{
    try {
        return ($discount_amount * 100) / $total_amount;
    } catch (\Throwable $th) {
        return 0;
    };
}



function vatSetting()
{
    return Cache::remember('vatSetting', 86400, function () {
        return Vat::first();
    });
}



function today_from_system()
{
    $date = explode('-', setting('date_start_end'));
    $start = $date[0];
    $end = $date[1];

    $current_time = Carbon::now()->timezone('Asia/Dhaka')->format('a');
    $current_hour = strtotime(Carbon::now()->timezone('Asia/Dhaka')->format('h:i A')) < strtotime(fdate($end, 'h:i A'));
    $current_hour_pm = strtotime(fdate($end, 'h:i A')) < strtotime(Carbon::now()->timezone('Asia/Dhaka')->format('h:i A'));

    $am = $current_time == 'am' && fdate($end, 'a') == 'am';
    $pm = $current_time == 'pm' && fdate($end, 'a') == 'pm';

    if(($am && $current_hour) || ($pm && $current_hour_pm)){
        return Carbon::now()->timezone('Asia/Dhaka')->subDay(1)->format('Y-m-d');
    }

    return Carbon::now()->timezone('Asia/Dhaka')->format('Y-m-d');

}

function nightCount($check_in, $check_out)
{
    $start_date  = strtotime($check_in);
    $end_date    = strtotime($check_out);
    $datediff    = $end_date - $start_date;
    $night_count = round($datediff / (60 * 60 * 24));

    if($night_count <=0){
        $night_count = 1;
    }
    return $night_count;
}




function account_types()
{
    return AccountType::pluck('name', 'id');
}


/**
 * ----------------------------------------------------------------
 * MINUTE COUNT AS HUNDRED
 * ----------------------------------------------------------------
 */
function minuteCountAsHundred($time) {

    if (strpos($time, '.') !== false) {
        list($hour, $minute) = explode('.', $time);
        $minuteCount = (int)$minute * 100 / 60;
        $result = (float)$hour + ($minuteCount / 100);
        return $result;
    } else {
        return null;
    }
}
