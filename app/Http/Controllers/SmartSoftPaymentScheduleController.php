<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\SmartSoftPaymentSchedule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SmartSoftPaymentScheduleController extends Controller
{
    public function index()
    {
        $schedules = SmartSoftPaymentSchedule::query()->orderByDesc('id')->get();

        return view('smart-soft-payment-schedules.index', compact('schedules'));
    }

    public function store(Request $request): RedirectResponse
    {
        SmartSoftPaymentSchedule::query()->create([
            'amount' => $request->amount,
            'date' => $request->date,
            'alert_date' => Carbon::parse($request->date)->addDays(-$request->threshold)->format('Y-m-d'),
        ]);

        return back()->with('message', 'Payment Schedule Created Successfully!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        SmartSoftPaymentSchedule::query()
            ->where('id', $id)
            ->update([
                'amount' => $request->amount,
                'date' => $request->date,
                'alert_date' => Carbon::parse($request->date)->addDays(-$request->threshold)->format('Y-m-d'),
            ]);

        return back()->with('message', 'Payment Schedule Updated Successfully!');
    }

    public function ajaxAlert(): JsonResponse
    {
        $schedule = SmartSoftPaymentSchedule::query()
            ->where('is_paid', 0)
            ->whereDate('alert_date', '<=', date('Y-m-d'))
            ->orderBy('alert_date')
            ->first();

        $alert = '';
        $group = null;
        $overdue = false;

        if ($schedule) {
            $days = Carbon::today()->diffInDays($schedule->date, false);
            if ($days == 0) {
                $alert = 'Your software subscription fee is ' . $schedule->amount . 'TK. Today is the last day! Click Here to make payment!';
            } else {
                $alert = 'Your software subscription fee is ' . $schedule->amount . 'TK. Last day for payment is ' . fdate($schedule->date,'d/m/Y') . '. Click Here to make payment!';
            }

            $overdue = $days < 0;

            $group = Group::query()->first();
        }

        return response()->json([
            'alert' => $alert,
            'order_id' => optional($schedule)->id,
            'amount' => optional($schedule)->amount,
            'customer_name' => optional($group)->name,
            'customer_email' => optional($group)->email,
            'customer_mobile' => optional($group)->phone,
            'customer_address_1' => optional($group)->address,
            'customer_city' => 'Dhaka',
            'customer_state' => 'Dhaka',
            'customer_postcode' => 1100,
            'overdue' => $overdue
        ]);
    }

    public function destroy($id): RedirectResponse
    {
        SmartSoftPaymentSchedule::query()->where('id', $id)->delete();

        return back()->with('message', 'Payment Schedule Deleted Successfully!');
    }

    public function feedback(Request $request): RedirectResponse
    {
    
        SmartSoftPaymentSchedule::query()
            ->where('id', $request->order_id)
            ->update([
                'is_paid' => $request->is_paid,
            ]);

        if ($request->is_paid) {
            session()->put('payment-success', 'Payment is successful!');
            return redirect($request->opt_a);
        }

        session()->put('payment-fail', 'Try Again!');
        return redirect($request->opt_a);
    }
}
