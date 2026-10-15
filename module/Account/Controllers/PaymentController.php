<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\Payment;
use Module\Account\Services\AccPaymentService;

class PaymentController extends Controller
{
    use CheckPermission;

    public $service;

    public function __construct()
    {
        $this->service = new AccPaymentService();
    }


    public function index()
    {
        $this->hasAccess("acc_payments.index");

        $payments = Payment::query()
            ->with('supplier')
            ->latest('date')
            ->latest('id')
            ->get();

        return view('purchase.payments.index', compact('payments'));
    }

    public function create()
    {
        $this->hasAccess("acc_payments.create");

        $suppliers = \Module\Account\Models\Supplier::orderBy('name')->pluck('name', 'id');

        return view('purchase.payments.create', compact('suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("acc_payments.create");

        $request->validate([
            'supplier_id'    => 'required|integer|exists:acc_suppliers,id',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'transaction_no' => 'nullable|string|max:100',
            'remarks'        => 'nullable|string|max:500',
        ]);

        try {
            $payment = $this->service->store($request);
            $this->service->invoiceNumberService->setNextInvoiceNo($payment->company_id, 'Payment', date('Y'));
            $this->service->makeTransaction();

            return redirect()->route('acc-payments.index')->with('message', 'Payment Create Successful');
        } catch (\Exception $ex) {

            return redirect()->back()->withInput($request->all())->with('error', $ex->getMessage());
        }
    }

    public function edit(Payment $payment)
    {
        $this->hasAccess("acc_payments.edit");

        $suppliers = \Module\Account\Models\Supplier::orderBy('name')->pluck('name', 'id');

        return view('purchase.payments.edit', compact('payment', 'suppliers'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $this->hasAccess("acc_payments.edit");

        $request->validate([
            'supplier_id'    => 'required|integer|exists:acc_suppliers,id',
            'date'           => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'transaction_no' => 'nullable|string|max:100',
            'remarks'        => 'nullable|string|max:500',
        ]);

        try {
            $this->service->update($request, $payment);
            $this->service->makeTransaction();

            return redirect()->route('acc-payments.index')->with('message', 'Payment Update Successful');
        } catch (\Exception $ex) {

            return redirect()->back()->withInput($request->all())->with('error', $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->hasAccess("acc_payments.delete");

        try {
            $payment = Payment::find($id);

            if ($payment) {
                $payment->transactions()->delete();
                $payment->delete();
            }

            return redirect()->route('acc-payments.index')->with('message', 'Payment Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
