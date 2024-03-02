<?php

use Carbon\Carbon;
use App\Models\Currency;
use Illuminate\Support\Str;
use Module\Hotel\Models\Rooms;
use NumberToWords\NumberToWords;
use App\Models\CurrencyConversion;
use Illuminate\Support\HtmlString;
use Module\CRM\Models\CRMCustomer;
use Module\Hotel\Models\AccountType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Module\HRM\Models\Employee\Employee;
use Module\Hotel\Models\HotelTransection;
use Module\HRM\Models\SalaryDeductionConfig;
use Module\Hotel\Services\StoreAccountService;
use Module\HRM\Models\Salary\DisbursementType;
use Module\Hotel\Models\HotelTransactionLedger;
use Module\Account\Models\Account as ModelsAccount;
use Module\HRM\Models\Sync\AttendanceSyncCronJobTimeSchedule;


function getFile($path = null)
{
    if ($path == null) {
        return '';
    }
    try {
        // return '';
        return Storage::cloud()->url($path);
    } catch (\Exception $ex) {
        return '';
    }
}


function getSidebarName($menu)
{
    $lower_menu = strtolower($menu->name);
    $sidebar_path = 'partials.sidebars.__sidebar_' . Str::snake($lower_menu);
    $sidebar_path = str_replace('&', '', $sidebar_path);

    return $sidebar_path;
}

function getCrmCompany($companyId)
{
    $crmCompany = CRMCustomer::find($companyId);

    return $crmCompany->org_name;
}

function getCrmCompanyAddress($companyId)
{
    $crmCompany = CRMCustomer::find($companyId);

    return $crmCompany->address;
}



function oldSelect($field_name, $value, $defaultValue = null): string
{
    return old($field_name, $defaultValue) == $value ? 'selected' : '';
}




function requestSelect($field_name, $value, $defaultValue = null): string
{
    return request($field_name, $defaultValue) == $value ? 'selected' : '';
}




function currencyToWords($num): string
{
    return str_replace('-', ' ', (new NumberToWords)->getCurrencyTransformer('en')->toWords(str_contains($num, '.') ? ((float)$num * 100) : $num, 'USD'));
}




//---------------------------------------------------//
//                GET CURRENT CURRENCY               //
//---------------------------------------------------//
function getCurrentCurrencyName(){

    return optional(Currency::where('id', setting('root_currency'))->select('name')->first())->name;

}



function getCurrentCurrencyRate($default = 'bdt')
{
    $selectedCurrency = CurrencyConversion::where('currency_id', 141)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    if ($default == 'bdt') {
        if (setting('root_currency') == 12) {
            return 1;
        }
        return $selectedCurrency->rate;
    }
    if ($default == 'myr') {
        if (setting('root_currency') == 96) {
            return 1;
        }
        return $selectedCurrency->rate;
    }
    if ($default == 'usd') {
        if (setting('root_currency') == 141) {
            return 1;
        }
        return 1 / $selectedCurrency->rate;
    }
    if ($default == 'sr') {
        if (setting('root_currency') == 116) {
            return 1;
        }
        return 1 / $selectedCurrency->rate;
    }

}

function convertToBDTCurrency($amount, $currency_id = 0)
{
    if (getSourceType() != 'Booking') {
        return $amount;
    }
    $selectedCurrency = CurrencyConversion::where('currency_id', 141)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();
    if (setting('root_currency') == 141 || $currency_id == 141) {

        return round($selectedCurrency->rate * $amount, 0);
    }
    return $amount;

    // return Cache::remember('convertToBdt', 86400, function () use ($amount) {
    //     $selectedCurrency = CurrencyConversion::where('currency_id', 141)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();
    //     if (setting('root_currency') == 141) {
    //         return round($selectedCurrency->rate * $amount, 0);
    //     }
    //     return $amount;
    // });


}

function setSourceType($source){
    return Session::put('source', $source);
}


function getSourceType(){
    return Session::get('source');
}


function calculateCurrencyAmount($amount, $ignore = 0)
{
    $selectedCurrency = CurrencyConversion::where('currency_id', 141)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    // if (setting('root_currency') == 141) {
    //     $amount = 1 / $selectedCurrency->rate * $amount;
    // }
    // if (setting('root_currency') == 96) {
    //     $amount = 1 / $selectedCurrency->rate * $amount;
    // }
    // if (setting('root_currency') == 116) {
    //     $amount = 1 / $selectedCurrency->rate * $amount;
    // }

    if($ignore == 1){
        return $amount;
    }

    return number_format($amount, 2);

}


function amount($amount, $tamount = 0)
{
    if($amount > 0)
        return $amount;
    return $tamount;
}




//---------------------------------------------------//
//                 GET BDT CURRENCY RATE             //
//---------------------------------------------------//
function getBdtCurrencyRate(){

    return optional(CurrencyConversion::where('currency_id', 12)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first())->rate;

}





//---------------------------------------------------//
//                 GET BDT CURRENCY RATE             //
//---------------------------------------------------//
function getBdtCurrentRateWithoutDevide(){

    $selectedCurrency = CurrencyConversion::where('currency_id', 12)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    return $selectedCurrency->rate ;
}





//---------------------------------------------------//
//            CONVERT CURRENCY [USD to BDT]          //
//---------------------------------------------------//
function convertCurrencyByUSD($number)
{
    $selectedCurrency = CurrencyConversion::where('currency_id', 141)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    return $convertCurrency  = 1/$selectedCurrency->rate * $number;

    return number_format($convertCurrency, 2);
}



//---------------------------------------------------//
//           CONVERT CURRENCY [BDT to USD]           //
//---------------------------------------------------//
function convertCurrencyByBDT($number)
{
    $selectedCurrency = CurrencyConversion::where('currency_id', 12)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    $convertCurrency  = (1 / $selectedCurrency->rate) * $number;

    return number_format($convertCurrency, 2);
}



//---------------------------------------------------//
//                  CONVERT CURRENCY                 //
//---------------------------------------------------//
function convertCurrency($number){

    $selectedCurrency = CurrencyConversion::where('currency_id', setting('root_currency'))->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first();

    $convertCurrency  = (1 / $selectedCurrency->rate) * $number;

    return $convertCurrency;
}




//---------------------------------------------------//
//                GET CURRENT CURRENCY               //
//---------------------------------------------------//
function currencySign(){

    $sign = setting('root_currency');

    switch ($sign) {
        case '141':
            return '$';
            break;

        case '12':
            return '৳';
            break;

        case '116':
            return 'SR';
            break;

        case '96':
            return 'RM';
            break;

        default:
            return '৳';
            break;
    }

}



function inWords($amount): string
{
    $f = new NumberFormatter(locale_get_default(), \NumberFormatter::SPELLOUT);

    return ucwords(str_replace('-', ' ', $f->format($amount)));
}




function updateEmploymentPosition($request, $model)
{
    $oldModel = clone ($model);

    $data = $model->with('employee.active_employment')->latest()->take(300)->whereDoesntHave('position')->get();

    $count = 0;

    foreach ($data as $key => $item) {
        $item->update([
            'position_id' => optional(optional($item->employee)->active_employment)->id
        ]);
        $count++;
    }

    if ($count > 0) {
        $nextCount = $oldModel->whereDoesntHave('position')->count();
        $message = $count . ' Employment position updated. ';

        if ($nextCount > 300) {
            return $message . ' Reload for next 300 from ' . $nextCount;
        } else if ($nextCount > 0) {
            return $message . ' Reload for last ' . $nextCount;
        }
    }

    return;
}




function getValidationErrorMessage($name)
{
    return view('partials._error-message', ['name' => $name])->render();
}




function flipDate($date)
{
    return date('Y-m-d', strtotime($date));
}




function dateRange()
{
    $request = request();
    return [
        'from'  => $request->from ? flipDate($request->from) : date('Y-m-d'),
        'to'    => $request->to ? flipDate($request->to) : date('Y-m-d'),
    ];
}




function hasField($path)
{
    try {
        return config($path);
    } catch (\Exception $th) {
        return 1;
    }
}




function getDateArray($fromDate, $toDate)
{

    $toDate = Carbon::parse($toDate)->addDay();

    $period = new DatePeriod(
        new DateTime($fromDate),
        new DateInterval('P1D'),
        new DateTime($toDate)
    );


    $dates = [];

    foreach ($period as $key => $value) {

        $dates[] = $value->format('Y-m-d');
    }

    return $dates;
}




function company_id()
{
    if (auth()->id() == 1) {
        return;
    }

    return auth()->user()->company_id;
}




function status($signal)
{
    if ($signal) {
        return new HtmlString("<span class=\"badge badge-success\"><i class='fa fa-check-circle text-white'></i> Active</span>");
    } else {
        return new HtmlString("<span class=\"badge badge-warning\"><i class='fa fa-times-circle text-white'></i> Inactive</span>");
    }
}


function sex($sex)
{
    switch ($sex) {
        case 3:
            return 'Child / Baby 👶';
            break;
        case 2:
            return 'Female 👩';
            break;
        case 1:
            return 'Male 👨';
            break;
    }
}


function BDT($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;

    $str = array();

    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );

    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');

    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else $str[] = null;
    }
    $Taka = implode('', array_reverse($str));

    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Poysha' : '';


    return ($Taka ? $Taka . ' Taka Only ' : '');
}


function convert_number($number)
{
    $my_number = $number;

    if (($number < 0) || ($number > 999999999))
    {
    throw new Exception("Number is out of range");
    }
    $Kt = floor($number / 10000000); /* Koti */
    $number -= $Kt * 10000000;
    $Gn = floor($number / 100000);  /* lakh  */
    $number -= $Gn * 100000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */

    $res = "";

    if ($Kt)
    {
        $res .= convert_number($Kt) . " Koti ";
    }
    if ($Gn)
    {
        $res .= convert_number($Gn) . " Lakh";
    }

    if ($kn)
    {
        $res .= (empty($res) ? "" : " ") .
            convert_number($kn) . " Thousand";
    }

    if ($Hn)
    {
        $res .= (empty($res) ? "" : " ") .
            convert_number($Hn) . " Hundred";
    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n)
    {
        if (!empty($res))
        {
            $res .= " and ";
        }

        if ($Dn < 2)
        {
            $res .= $ones[$Dn * 10 + $n];
        }
        else
        {
            $res .= $tens[$Dn];

            if ($n)
            {
                $res .= "-" . $ones[$n];
            }
        }
    }

    if (empty($res))
    {
        $res = "zero";
    }

    return $res;

}




//---------------------------------------------------//
//         GET CURRENT CURRENCY CONVERSION           //
//---------------------------------------------------//
function getCurrencyConversion($currencyId){

    return optional(CurrencyConversion::where('currency_id', $currencyId)->where('effected_date', '<=' ,date('Y-m-d'))->orderBy('effected_date','DESC')->first())->id;

}




//---------------------------------------------------//
//                 GET TOTAL PAYMENT AMOUNT          //
//---------------------------------------------------//
function getTotalPaymentAmount($audit_id, $account_type_id, $source_type){

    return HotelTransactionLedger::whereHas('transaction', function($q) use($audit_id){
                                $q->whereHas('night_audits', function($query) use($audit_id){
                                    $query->where('audit_id', $audit_id);
                                });
                            })
                            ->where('source_type', $source_type)
                            ->where('payment_type', $account_type_id)
                            ->sum('in');

}


//---------------------------------------------------//
//         GET TOTAL PAYMENT AMOUNT BOOKING          //
//---------------------------------------------------//
function getTotalPaymentAmountBooking($audit_id, $account_type_id, $source_type){

    return HotelTransection::whereHas('night_audits', function($query) use($audit_id){
                                $query->where('audit_id', $audit_id);
                            })
                            ->where('source_type', $source_type)
                            ->where('account_type_id', $account_type_id)
                            ->sum('collection');

}

//---------------------------------------------------//
//         GET TOTAL PAYMENT  TYPE AMOUNT            //
//---------------------------------------------------//
function getTotalPaymentTypeAmount($account_type_id, $source_type){

    $hotelTransaction = HotelTransection::where('source_type', $source_type)
                            ->when(request()->filled('from_date'), function ($qr){
                                $qr->where('date', '>=', request('from_date'));
                            })
                            ->when(request()->filled('to_date'), function ($qr) {
                                $qr->where('date', '<=', request('to_date'));
                            });

    $collection = $hotelTransaction;
    $due        = $hotelTransaction;
    $totalDue   = $hotelTransaction;

    $data['collection'] = $collection->whereHas('transaction_ledgers', function ($query) use ($account_type_id) {
                            $query->where('payment_type', $account_type_id);
                        })
                        // ->sum('in')
                        ->sum('collection');

    $data['due']        = $due->sum('due_amount');
    $data['totalDue']   = $totalDue->sum('total_due_amount');

    return $data;

}



//---------------------------------------------------//
//      GET TOTAL PAYMENT AMOUNT  IN INVOICE         //
//---------------------------------------------------//
function getTotalPaymentTypeInvoice($account_type_id, $source_id, $source_type){

    return HotelTransactionLedger::where('source_id', $source_id)
                                    ->where('source_type', $source_type)
                                    ->where('payment_type', $account_type_id)
                                    ->sum('in');
}


//---------------------------------------------------//
//                    GET TOTAL DUE AMOUNT           //
//---------------------------------------------------//
function getTotalPaymentDueAmount($source_type){

    $hotelTransaction = HotelTransection::where('source_type', $source_type)
                            ->when(request()->filled('from_date'), function ($qr){
                                $qr->where('date', '>=', request('from_date'));
                            })
                            ->when(request()->filled('to_date'), function ($qr) {
                                $qr->where('date', '<=', request('to_date'));
                            });

        $due        = $hotelTransaction;
        $totalDue   = $hotelTransaction;

        $data['due']        = $due->sum('due_amount');
        $data['totalDue']   = $totalDue->sum('total_due_amount');

        return $data;

}





//---------------------------------------------------//
//         GET CURRENT CURRENCY CONVERSION           //
//---------------------------------------------------//
function getAvailableRoom($category_id, $check_in, $check_out){

    return Rooms::where('room_category', $category_id)
                ->withCount(['booking_dates as is_booked' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 2);
                    });
                }])
                ->withCount(['booking_dates as is_checkin' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 1);
                    });
                }])
                ->withCount(['booking_dates as is_reservation' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out)
                            ->where('status', 0);
                    });
                }])
                ->withCount(['booking_dates as today_checkout' => function ($qr) use ($check_in, $check_out) {
                    $qr->where(function ($q) use ($check_in, $check_out) {
                        $q->where('date', '>=', $check_in)
                            ->where('date', '<=', $check_out);
                    })->whereHas('booking', function ($qr) use($check_in) {
                        $qr->where('check_out_date', $check_in)->where('status', 1);
                    });
                }])
                ->where('status', 1)->get();

}






function getPaymentTypeAccount($payment_way)
{

    $payment_way = AccountType::find($payment_way);

    if (!$payment_way) {

        return ModelsAccount::find(55);

    }

    if($payment_way->account_id != null){

        return ModelsAccount::find($payment_way->account_id);

    }
    else{

        // ACC ACCOUNT CREATE
        $account_subsidiary_id  = strtolower($payment_way->name) == 'cash' ? 11 : 10;
        $account                = (new StoreAccountService())->storeAccount($payment_way->name, $account_subsidiary_id);


        // UPDATING ACCOUNT ID OF ACCOUNT TYPE AFTER CREATING ACCOUNT
        $payment_way->update([
            'account_id'    => $account->id
        ]);

        return $account;

    }

}




/**
 * ----------------------------------------------------------------
 * HRM HELPER METHOD
 * ----------------------------------------------------------------
 */

 function logo()
 {
     if(count(auth()->user()->companies ?? []) == 1){
         if(optional(optional(auth()->user())->companies->first())->logo != 'default.png' && file_exists('uploads/company/'. optional(optional(auth()->user())->companies()->first())->logo)){
             return '<img style="height: 40px !important" class="logo" src="'. asset('uploads/company/'. optional(optional(auth()->user())->companies->first())->logo) .'"'. 'alt="">';
         }

         return '<small class="text-primary font-weight-bold" style="font-weight: 600">
                 <span class="white">'.
                     optional(optional(auth()->user())->companies()->first())->name
                 .'</span>
             </small>';

     }

     if(optional(optional(optional(auth()->user())->company)->group)->logo && file_exists('uploads/group/'. optional(optional(optional(auth()->user())->company)->group)->logo)){

         return '<img style="height: 40px !important" class="logo" src="'.asset('uploads/group/'. optional(optional(optional(auth()->user())->company)->group)->logo) .'"'. 'alt="">';
     }
     return '<small class="text-primary font-weight-bold" style="font-weight: 600">
                 <span class="white">'.
                     optional(optional(optional(auth()->user())->company)->group)->name
                 .'</span>
             </small>';

 }



 function calculateTime($times)
 {
     $minutes = 0;

     try {
         foreach ($times as $time) {
             $time = number_format($time, 2, '.', '');
             if ($time != '' || $time != null) {
                 try {
                     list($hour, $minute) = explode('.', $time);
                     $minutes += $hour * 60;
                     $minutes += $minute;
                 } catch (\Exception $ex) {
                     $minutes += 0;
                 }
             }
         }

         $hours = floor($minutes / 60);
         $minutes -= $hours * 60;

     } catch (\Throwable $th) {
         $hours  = 0;
         $minutes = 0;
     }
     return sprintf('%02d.%02d', $hours, $minutes);
 }


 function employee_full_ids()
 {
     return Cache::remember('employees', $oneDay = 86400, function () {
         return Employee::query()->permissionEmployment()->get(['id', 'name', 'employee_full_id']);
     });
 }

 function lateConfig($company_id)
 {
     return LateConfig::where('company_id', $company_id)->select(['id', 'company_id', 'days', 'count_type', 'leave_type_id', 'late_time'])->first();
 }


 function calculateHourByMinutes($numbers)
 {
     $todayOtHours   = 0;
     $hours          = 0;
     $minutes        = 0;
     $otHours        = 0;

     foreach ($numbers ?? [] as $number) {
         $overTime   = explode('.', $number);
         $minutes    += isset($overTime[1]) > 0 ? $overTime[1] / 60 : 0;
         $hours      += $overTime[0];
         $otHours    = $hours + $minutes;
     }

     $otHours        = explode('.', $otHours);
     $otMinutes      = isset($otHours[1]) > 0 ? abs('.' . $otHours[1]) * .6 : 0;
     $todayOtHours   = number_format($otHours[0] + $otMinutes, 2, '.', '');

     return $todayOtHours;
 }


 function timeToNumber($time)
 {
     $time   = explode('.', $time);
     $hour   = $time[0];
     $min    = $time[1] ?? 0;
     $array  = array_map('intval', str_split($min));
     $hours  = '.' . $array[0];
     $minutes = array_key_exists(1, $array) ? $array[1] : 0;

     $time = ($hours >= 0.6 ? 1 : $hours);

     $time = $time == 1 ? $time + str_pad($minutes, 2, '0', STR_PAD_LEFT) : $time . $minutes;
     return $hour + $time;
 }


 function ifDecimalValueIsZeroConvertToSixty($time)
 {
     try {
         $time   = explode('.', $time);
         $hour   = $time[0];
         $min    = $time[1];

         if ($min == 0) {
             $hour = $hour - 1;
             $min = 60;
         }

         $hourMinute = $hour . '.' . $min;

         return calculateTime([$hourMinute]);
     } catch (\Throwable $th) {
         return $time;
     }
 }



 function calculateWorkHour($checkInTime, $checkOutTime)
 {
     $totalWorkedHour = 0;

     if ($checkInTime != null & $checkOutTime != null) {
         $outTime            = Carbon::parse($checkOutTime);
         $inTime             = Carbon::parse($checkInTime);
         $totalWorkedHour    = $outTime->diff($inTime)->format('%H.%I');
     }

     return $totalWorkedHour;
 }




 function calculateOT($attendance)
 {
     $otCountingAfter    = optional(optional(optional($attendance)->company)->otConfig)->ot_counting_after ?? 0;

     $overTime           = 0.00;
     $getOverTime        = optional($attendance)->check_in_time != null
                         ? calculateTime([(new \Module\HRM\Services\AttendanceServiceV3)->getOverTime(optional($attendance)->date, optional($attendance)->check_out_time, optional($attendance)->attendance_type, optional($attendance)->shift, optional($attendance)->check_in_time)])
                         : 0;

     if (calculateTime([$otCountingAfter]) <= $getOverTime) {
         $overTime = $getOverTime;
     }

     return $overTime;
 }


 function disbursementTypes()
 {
     return DisbursementType::orderBy('name')->get();
 }


 function disbursementTypesWithoutOTAndEarnLeave()
 {
     return DisbursementType::where('id', '!=', 2)->where('name', '!=', 'Earn Leave')->orderBy('name')->get();
 }


 function attendanceSyncCronJobTimeSchedules()
 {
     return AttendanceSyncCronJobTimeSchedule::pluck('time');
 }


 function joiningMonthTotalDays($employee)
 {
     $joiningMonthLastDate   = Carbon::createFromFormat('Y-m-d', $employee->joining_date)->endOfMonth()->format('Y-m-d');
     $toDate                 = Carbon::createFromFormat('Y-m-d', $employee->joining_date);
     $fromDate               = Carbon::createFromFormat('Y-m-d', $joiningMonthLastDate);

     return $toDate->diffInDays($fromDate) + 1;
 }

function salaryDeductionConfigByIndex($index = 0)
{
    $salaryDeductionConfigs = SalaryDeductionConfig::active()->get();
    $salaryDeductionConfig = null;

    if (count($salaryDeductionConfigs) > 0 && $index == 0) {
        $salaryDeductionConfig = $salaryDeductionConfigs[$index];
    }

    if (count($salaryDeductionConfigs) > 1 && $index == 1) {
        $salaryDeductionConfig = $salaryDeductionConfigs[$index];
    }

    return optional($salaryDeductionConfig);
}


/**
 * ----------------------------------------------------------------
 * EMPLOYEE CARD NUMBER
 * ----------------------------------------------------------------
 */
function employeesCardNumbers(){

        return Cache::remember('employeesCardNumbers', $oneDay = 86400, function () {
            return Employee::query()->active()->permissionEmployment()->pluck('card_no');
        });
    }


/**
 * ----------------------------------------------------------------
 * DAY AS NUMBER
 * ----------------------------------------------------------------
 */
function dayNumber($dayName){

        $dayName = strtolower($dayName);
        if($dayName == 'saturday'){
            return 6;
        }else if($dayName == 'sunday'){
            return 0;
        }else if($dayName == 'monday'){
            return 1;
        }else if($dayName == 'tuesday'){
            return 2;
        }else if($dayName == 'wednesday'){
            return 3;
        }else if($dayName == 'thursday'){
            return 4;
        }else if($dayName == 'friday'){
            return 5;
        }

}

