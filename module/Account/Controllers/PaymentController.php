<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Payment ;

class PaymentController extends Controller
{
    use CheckPermission;


    public function index()
    {
        $this->hasAccess("acc_payments.index");

        return view('purchase.payments.index');
    }

    public function create()
    {
        $this->hasAccess("acc_payments.create");

        return view('purchase.payments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_payments.create");

        return redirect()->route('acc_payments.index')->with('message', 'Payment Create Successful');
    }

    public function edit(Payment $payment)
    {
        $this->hasAccess("acc_payments.edit");


        return view('purchase.payments.edit');
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $this->hasAccess("acc_payments.edit");


        return redirect()->route('acc_payments.index')->with('message', 'Payment Update Successful');
    }


    public function destroy($id)
    {
        $this->hasAccess("acc_payments.delete");

        try {
            Payment ::destroy($id);

            return redirect()->route('acc_payments.index')->with('message', 'Payment Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
