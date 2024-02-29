<?php


namespace Module\Account\Services;


use Illuminate\Http\Request;
use Module\Account\Models\Account;
use Module\Account\Models\Purchase;
use Module\Account\Models\Transaction;

class SupplierLedgerReportService
{



    public function getSupplierLedgerReport(Request $request)
    {
        $accounts = Account::where('account_type_id', '=', 3)->get();
        $transactions = Transaction::where('transaction_type_id', '=', 1)->get();
        return view('account::supplier-ledger-report', compact('accounts', 'transactions'));
    }




    public function getLedger(Request $request)
    {

        $per_page = 30;

        $data['selected_account'] = Account::find($request->account_id);

        $data['paginate_debit_balance'] = 0;
        $data['paginate_credit_balance'] = 0;

        $balance = Transaction::query()
            ->searchByField('company_id')
            ->where('account_id', $request->account_id)
            ->where('date', '<',  fdate($request->from ?? date('Y-m-d')))
            ->get();

        $data['debit_balance']  = $balance->sum('debit_amount') ?? 0;
        $data['credit_balance'] = $balance->sum('credit_amount') ?? 0;



        $data['transactions'] = Transaction::query()
            ->with('transactionable')
            ->searchByField('company_id')
            ->where('account_id', $request->account_id)
            ->when($request->from, function ($q) use ($request) {
                $q->where('date', '>=', $request->from);
            })
            ->when($request->to, function ($q) use ($request) {
                $q->where('date', '<=', $request->to);
            });

        $data['transactions'] = $request->print || request('export_type')
            ? $data['transactions']->get()
            : $data['transactions']->paginate($per_page);



        if ($request->filled('page')) {

            $paginate_balance = Transaction::query()
                ->with('transactionable')
                ->searchByField('company_id')
                ->where('account_id', $request->account_id)
                ->when($request->from, function ($q) use ($request) {
                    $q->where('date', '>=', $request->from);
                })
                ->when($request->to, function ($q) use ($request) {
                    $q->where('date', '<=', $request->to);
                })
                ->limit(($request->page - 1) * $per_page)
                ->get();


            $data['paginate_debit_balance'] = $paginate_balance->sum('debit_amount');
            $data['paginate_credit_balance'] = $paginate_balance->sum('credit_amount');
        }

        if(!$request->filled('print') && !request('export_type')) {

            if($data['transactions']->currentPage() == $data['transactions']->lastPage()) {


            $total_value_query = Transaction::query()
                                ->with('transactionable')
                                ->searchByField('company_id')
                                ->where('account_id', $request->account_id)
                                ->when($request->from, function ($q) use ($request) {
                                    $q->where('date', '>=', $request->from);
                                })
                                ->when($request->to, function ($q) use ($request) {
                                    $q->where('date', '<=', $request->to);
                                });

                $data['grand_total_debit_balance'] = (clone $total_value_query)->sum('debit_amount');
                $data['grand_total_credit_balance'] = (clone $total_value_query)->sum('credit_amount');
            }
        }

        return $data;
    }






    public function supplierPurchaseReport($request)
    {
        $purchase                           = Purchase::query()->with('supplier:id,name','company:id,name')->dateFilter()->searchByField('company_id')->searchByField('supplier_id');

        $data['purchases']                  = $request->print || request('export_type') ? $purchase->get() : $purchase->paginate(30);

        $data['grand_total_amount']         = $purchase->get()->sum('total_amount');
        $data['grand_total_paid_amount']    = $purchase->get()->sum('paid_amount');


        return $data;
    }
}
