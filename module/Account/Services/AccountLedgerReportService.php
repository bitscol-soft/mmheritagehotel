<?php


namespace Module\Account\Services;


use Illuminate\Http\Request;
use Module\Account\Models\Account;
use Module\Account\Models\Transaction;

class AccountLedgerReportService
{
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

        $data['debit_balance'] = $balance->sum('debit_amount');
        $data['credit_balance'] = $balance->sum('credit_amount');



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
}
