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

        // TODO: dedicated payment form (module is a stub in this repo)
        return redirect()->route('acc-payments.index')
            ->with('info', 'Payment entry form is not available yet; payments are recorded through the voucher module.');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_payments.create");

        return redirect()->route('acc_payments.index')->with('message', 'Payment Create Successful');
    }

    public function edit(Payment $payment)
    {
        $this->hasAccess("acc_payments.edit");

        // TODO: dedicated payment form (module is a stub in this repo)
        return redirect()->route('acc-payments.index')
            ->with('info', 'Payment edit form is not available yet.');
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
