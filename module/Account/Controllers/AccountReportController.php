<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Services\ExportService;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Module\Account\Models\Account;

use Module\Account\Models\AccountGroup;
use Module\Account\Models\AccountSubsidiary;
use Module\Account\Models\Customer;
use Module\Account\Models\Supplier;
use Module\Account\Models\Transaction;
use Module\Account\Services\AccountLedgerReportService;
use Module\Account\Services\DataService;
use Module\Account\Services\JournalLedgerReportService;
use Module\Account\Services\SubsidiaryWiseLedgerReportService;
use Module\Account\Services\SupplierLedgerReportService;
use Module\Account\Services\TransactionLedgerReportService;
use Module\Account\Services\VoucherReportService;
use Illuminate\Support\Facades\Auth;

class AccountReportController extends Controller
{
    use CheckPermission;

    private $dataService;

    private $reportService;

    private $journalLedger;

    private $subsidiaryLedger;

    private $transactionLedger;

    private $export_service;









    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->dataService          = new DataService();

        $this->reportService        = new AccountLedgerReportService();

        $this->journalLedger        = new JournalLedgerReportService();

        $this->transactionLedger    = new TransactionLedgerReportService();

        $this->subsidiaryLedger     = new SubsidiaryWiseLedgerReportService();

        $this->export_service       = new ExportService();
    }











    /*
     |--------------------------------------------------------------------------
     | ACCOUNT LEDGER REPORT
     |--------------------------------------------------------------------------
    */
    public function accountLedgerReport(Request $request)
    {
        $this->hasAccess("report.account-ledger");

        $data1 = $this->dataService->getAccountData(['accounts']);

        $data2 = $this->reportService->getLedger($request);

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data2, 'reports/account-ledger/export/', 'Account Ledger Report List');

        }

        $data1['companies']  = Company::userCompanies();

        $view = 'reports.account-ledger.' . ($request->print ? 'print' : 'index');

        return view($view, $data1, $data2);
    }











    /*
     |--------------------------------------------------------------------------
     | VOUCHER REPORT
     |--------------------------------------------------------------------------
    */
    public function getVoucherReport(Request $request)
    {
        $this->hasAccess("report.ledger-journal");

        $data1 = $this->dataService->getAccountData(['accounts']);

        $data2 = (new VoucherReportService)->getReportData($request);


        $data1['companies']  = Company::userCompanies();


        if(request('export_type')){

            $data['vouchers']       = $data2;
            $data['paginate']       = 0;

            return $this->export_service->exportData($data2, 'reports/voucher-reports/export/', 'Voucher Report List');

        }

        $view = 'reports.voucher-reports.' . ($request->print ? 'print' : 'index');

        return view($view, $data1, $data2);
    }











    /*
     |--------------------------------------------------------------------------
     | LEDGER JOURNAL REPORT
     |--------------------------------------------------------------------------
    */
    public function JournalReport(Request $request)
    {
        $this->hasAccess("report.ledger-journal");

        $data = $this->journalLedger->getJournalReport($request);


        if(request('export_type')){

            $data['transactions']       = $data['transactions'];
            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/journal-report/export/', 'Journal Report List');

        }

        $data['companies']  = Company::userCompanies();

        $view = 'reports.journal-report.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | TRIAL BALANCE
     |--------------------------------------------------------------------------
    */
    public function trialBalanceReport(Request $request)
    {
        $this->hasAccess("report.trial-balance");

        $accountGroups = $this->transactionLedger->getTrialBalanceReportData($request);
        $companies  = Company::userCompanies();

        if(request('export_type')){

            $data['accountGroups']      = $accountGroups;
            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/trial-balance/export/', 'Trial Balance Report List');

        }

        $view = 'reports.trial-balance.' . ($request->print ? 'print' : 'index');

        return view($view, compact('accountGroups', 'companies'));
    }












    /*
     |--------------------------------------------------------------------------
     | INCOME STATEMENT
     |--------------------------------------------------------------------------
    */
    public function incomeStatement(Request $request)
    {
        $this->hasAccess("report.income-statement");


        $data                   = $this->transactionLedger->getIncomeStatementReportData($request);
        $data['companies']      = Company::userCompanies();



        $data['companyNames']   = [];

        if (request()->filled('company_id')) {

            $data['companyNames']   = Company::query()
                                    ->when(request()->filled('company_id'), function ($q) {
                                        $q->whereIn('id', request('company_id'));
                                    })
                                    ->get()
                                    ->map(function ($item) {
                                        return [
                                            'name' => $item->name
                                        ];
                                    })
                                    ->flatten()
                                    ->toArray();
        }

        if ($request->routeIs('report.income-expense-statement')) {

            if(request('export_type')){

                $data['paginate']           = 0;

                return $this->export_service->exportData($data, 'reports/income-statement/export/', 'Income Expense Statement Report List');

            }

            $view = 'reports.income-expense-statement.' . ($request->print ? 'print' : 'index');

        }

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/income-statement/export/', 'Income Statement Report List');

        }

        $view = 'reports.income-statement.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | EQUITY STATEMENT
     |--------------------------------------------------------------------------
    */
    public function equityStatement(Request $request)
    {
        $this->hasAccess("report.equity-statement");

        $item                       = $this->transactionLedger->getIncomeStatementReportData($request);

        $revenues = $item['revenues']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $purchases = $item['purchases']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $expenses = $item['expenses']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $depreciations = $item['depreciations']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $equity = $item['equity']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });


        $data['profit_and_loss']    = $revenues - $purchases - $expenses - $depreciations;
        $data['equity']             = $equity;
        $data['companies']          = Company::userCompanies();

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/equity-statement/export/', 'Equity Statement Report List');

        }

        $view                       = 'reports.equity-statement.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }












    /*
     |--------------------------------------------------------------------------
     | CHART OF ACCOUNT REPORT
     |--------------------------------------------------------------------------
    */
    public function chartOfAccountReport(Request $request)
    {
        $this->hasAccess("report.chart-of-account");

        $data['companies']  = Company::userCompanies();

        $data['accounts'] = Account::query()
                            ->companies()
                            ->searchByField('company_id')
                            ->searchByField('balance_type')
                            ->likeSearch('name')
                            ->whereDateFilter('created_at')
                            ->orderBy('name')
                            ->withCount(['transaction_items as debit' => function ($quer) {
                                $quer->searchByField('company_id')->select(DB::Raw('SUM(debit_amount)'));
                            }])->withCount(['transaction_items as credit' => function ($quer) {
                                $quer->searchByField('company_id')->select(DB::Raw('SUM(credit_amount)'));
                            }]);


        if ($request->print) {

            $data['accounts']       = $data['accounts']->get();

        }elseif(request('export_type')){

            $data['accounts']      = $data['accounts']->get();
            $data['paginate']       = 0;

            return $this->export_service->exportData($data, 'reports/chart-of-account/export/', 'Chart of Account List');

        } else {

            $data['accounts']       = $data['accounts']->paginate(30);

        }

        return view('reports.chart-of-account.' . ($request->print ? 'print' : 'index'), $data);
    }












    /*
     |--------------------------------------------------------------------------
     | CUSTOMER LEDGER
     |--------------------------------------------------------------------------
    */
    public function customerLedgerReport(Request $request)
    {
        $this->hasAccess("report.customer-ledger");


        $customer = Customer::select('name', 'id', 'account_id')->get();

        $transactions = Transaction::query()
            ->searchByField('company_id')
            ->where('account_id', $request->account_id)
            ->where('date', '<',  fdate($request->from ?? today()))
            ->get();



        $data['companies']  = Company::userCompanies();
        $data['balance'] = $transactions->sum('debit_amount') - $transactions->sum('credit_amount');

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

        $data['transactions'] = $request->print
            ? $data['transactions']->get()
            : $data['transactions']->paginate(30);

        return view('reports.customer-ledger.' . ($request->print ? 'print' : 'index'), compact('customer'), $data);
    }












    /*
     |--------------------------------------------------------------------------
     | ACCOUNT RECEIVABLE
     |--------------------------------------------------------------------------
    */
    public function accountReceivableReport(Request $request)
    {
        $this->hasAccess("report.customer-ledger");


        $data['companies'] = Company::userCompanies();

        $query = Account::query()->asset()->currentAsset()->where('account_subsidiary_id', 8);

        $data['transactions'] = (clone $query)
                                ->when($request->filled('account_id'), function($q) use($request) {
                                    $q->where('id', $request->account_id);
                                })
                                ->withCount(['transaction_items as balance' => function ($quer) {
                                    $quer->searchByField('company_id')->select(DB::Raw('SUM(debit_amount - credit_amount)'));
                                }]);

        $data['accounts'] = $query->get(['name', 'id']);

        if ($request->print) {
            $data['transactions']       = $data['transactions']->get();

        }elseif(request('export_type')){
            $data['transactions']       = $data['transactions']->get();
            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/account-receivables/export/', 'Account Receivable List');

        } else {
            $data['transactions']       = $data['transactions']->paginate(50);

        }

        return view('reports.account-receivables.' . ($request->print ? 'print' : 'index'), $data);
    }












    /*
     |--------------------------------------------------------------------------
     | ACCOUNT PAYABLE
     |--------------------------------------------------------------------------
    */
    public function accountPayableReport(Request $request)
    {
        $this->hasAccess("report.customer-ledger");

        $data['companies'] = Company::userCompanies();


        $query = Account::query()->liabilities()->where('account_control_id', 3)->where('account_subsidiary_id', 4);

        $data['transactions'] = (clone $query)
                                ->when($request->filled('account_id'), function($q) use($request) {
                                    $q->where('id', $request->account_id);
                                })
                                ->withCount(['transaction_items as balance' => function ($quer) {
                                    $quer->searchByField('company_id')->select(DB::Raw('SUM(debit_amount - credit_amount)'));
                                }]);

        $data['accounts'] = $query->get(['name', 'id']);


        if ($request->print) {
            $data['transactions']       = $data['transactions']->get();

        }elseif(request('export_type')){
            $data['transactions']       = $data['transactions']->get();
            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/account-payables/export/', 'Account Payables List');

        } else {
            $data['transactions']       = $data['transactions']->paginate(50);

        }

        return view('reports.account-payables.' . ($request->print ? 'print' : 'index'), $data);
    }











    /*
     |--------------------------------------------------------------------------
     | SUPPLIER LEDGER
     |--------------------------------------------------------------------------
    */
    public function supplierLedgerReport(Request $request)
    {

        $this->hasAccess("report.supplier-ledger");

        // For Supplier Ledger Report
        $data['supplier']   = Supplier::select('name', 'id', 'account_id')->get();
        $data['companies']  = Company::userCompanies();


        $data2 = (new SupplierLedgerReportService)->getLedger($request);

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data2, 'reports/supplier-ledger/export/', 'Supplier Ledger Report List');

        }

        return view('reports.supplier-ledger.' . ($request->print ? 'print' : 'index'), $data, $data2);
    }





    /*
     |--------------------------------------------------------------------------
     | SUPPLIER REPORT
     |--------------------------------------------------------------------------
    */
    public function supplierReport(Request $request)
    {

        $this->hasAccess("report.supplier-ledger");


        $data                   = (new SupplierLedgerReportService)->supplierPurchaseReport($request);
        $data['suppliers']      = Supplier::select('name', 'id', 'account_id')->get();
        $data['companies']      = Company::userCompanies();


        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/supplier/export/', 'Supplier Report List');

        }

        return view('reports/supplier.' . ($request->print ? 'print' : 'index'), $data);
    }

















    /*
     |--------------------------------------------------------------------------
     | TRIAL BALANCE
     |--------------------------------------------------------------------------
    */
    public function transactionLedgerReport(Request $request)
    {
        $this->hasAccess("account.transaction.ledger.reports");

        $accountGroups = $this->transactionLedger->getTrialBalanceReportData($request);

        $view = 'reports.transaction-ledger.category-' . ($request->print ? 'print' : 'index');

        return view($view, compact('accountGroups'));
    }

    public function ledgerJournalReport(Request $request)
    {
        $this->hasAccess("	report.ledger-journal");

        $data = $this->dataService->getAccountData(['accounts']);
        $data2 = $this->journalLedger->getLedger($request);

        $view = 'reports.ledger-journal.' . ($request->print ? 'print' : 'index');

        return view($view, $data, $data2);
    }

    public function subsidiaryWiseLedgerReport(Request $request)
    {
        $this->hasAccess("report.subsidiary-wise-ledger");

        $data = $this->dataService->getAccountData(['accountSubsidiaries']);
        $data2 = $this->subsidiaryLedger->getLedger($request);
        $data['companies']  = Company::userCompanies();

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data2, 'reports/subsidiary-wise-ledger/export/', 'Subsidiary wise ledger List');

        }

        $view = 'reports.subsidiary-wise-ledger.' . ($request->print ? 'print' : 'index');

        return view($view, $data, $data2);
    }

    public function expenseAnalysisReport(Request $request)
    {
        $this->hasAccess("account.expense.analysis.reports");

        $data = $this->dataService->getAccountData(['accountControls', 'accountSubsidiaries']);

        $data['accountSubsidiaries'] = AccountSubsidiary::query()
            ->where('account_control_id', $request->account_control_id)
            ->select('id', 'name')
            ->get();

        $data['accounts'] = Account::query()
            ->when($request->account_subsidiary_id, function ($q) use ($request) {
                $q->where('account_subsidiary_id', $request->account_subsidiary_id);
            })
            ->when($request->account_control_id, function ($q) use ($request) {
                $q->where('account_control_id', $request->account_control_id);
            })
            ->select('id', 'name')
            ->get();

        $data['transaction_items'] = Transaction::query()
            ->whereHas('account', function ($q) use ($request) {
                $q->where('account_group_id', 5)
                    ->with('accountSubsidiary', 'accountControl')
                    ->where('balance_type', 'Debit')
                    ->when($request->account_subsidiary_id, function ($r) use ($request) {
                        $r->where('account_subsidiary_id', $request->account_subsidiary_id);
                    })
                    ->when($request->account_control_id, function ($r) use ($request) {
                        $r->where('account_control_id', $request->account_control_id);
                    })
                    ->when($request->account_id, function ($r) use ($request) {
                        $r->where('account_id', $request->account_id);
                    });
            })
            ->where('date', '>=', $request->from ?? date('Y-m-d'))
            ->where('date', '<=', $request->to ?? date('Y-m-d'))
            ->withCount(['account as account_subsidiary_id' => function ($q) {
                $q->select(DB::raw('SUM(account_subsidiary_id)'));
            }])
            ->withCount(['account as account_control_id' => function ($q) {
                $q->select(DB::raw('SUM(account_control_id)'));
            }]);

        if ($request->print) {
            $data['transaction_items'] = $data['transaction_items']->get();
        } else {
            $data['transaction_items'] = $data['transaction_items']->paginate(30);
        }

        return view('reports.expense-analysis.' . ($request->print ? 'print' : 'index'), $data);
    }













    /*
     |--------------------------------------------------------------------------
     | BALANCE SHEET
     |--------------------------------------------------------------------------
    */
    public function balanceSheetReport(Request $request)
    {
        $this->hasAccess("report.balance-sheet");


        $item                       = $this->transactionLedger->getIncomeStatementReportData($request);


        $revenues = $item['revenues']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $purchases = $item['purchases']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $expenses = $item['expenses']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $depreciations = $item['depreciations']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $equity = $item['equity']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $data['equity_balance']     = $revenues + $equity - $purchases - $expenses - $depreciations;
        $data['companies']          = Company::userCompanies();


        $data['accountGroups'] = AccountGroup::with(['accountControls' => function ($q) use ($request) {
            $q->with(['accounts' => function ($qr) use ($request) {
                $qr->withCount(['transaction_items as debit_balance' => function ($qur) use ($request) {
                    return $qur
                        ->searchByField('company_id')
                        ->where('date', '<=', fdate($request->date ?? today()))
                        ->select(DB::Raw('SUM(debit_amount)'));
                }])
                    ->withCount(['transaction_items as credit_balance' => function ($qur) use ($request) {
                        return $qur
                            ->searchByField('company_id')
                            ->where('date', '<=', fdate($request->date ?? today()))
                            ->select(DB::Raw('SUM(credit_amount)'));
                    }]);
            }]);
        }])
        ->get();

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/balance-sheet/export/', 'Balance Sheet List');

        }

        $view = 'reports.balance-sheet.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }









    /*
     |--------------------------------------------------------------------------
     | CASH FLOW
     |--------------------------------------------------------------------------
    */
    public function cashFlowReport(Request $request)
    {
        $this->hasAccess("report.cash.flow");


        $item                       = $this->transactionLedger->getIncomeStatementReportData($request);


        $revenues = $item['revenues']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $purchases = $item['purchases']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $expenses = $item['expenses']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $depreciations = $item['depreciations']->accountControls->sum(function ($control) {
            return $control->accounts->sum('balance');
        });

        $data['equity_balance']     = $revenues - $purchases - $expenses - $depreciations;
        $data['depreciations']      = $depreciations;
        $data['companies']          = Company::userCompanies();

        $data['accountGroups'] = AccountGroup::with(['accountControls' => function ($q) use ($request) {
            $q->with(['accounts' => function ($qr) use ($request) {
                $qr->withCount(['transaction_items as debit_balance' => function ($qur) use ($request) {
                    return $qur
                        ->searchByField('company_id')
                        ->where('date', '<=', fdate($request->date ?? today()))
                        ->select(DB::Raw('SUM(debit_amount)'));
                }])
                    ->withCount(['transaction_items as credit_balance' => function ($qur) use ($request) {
                        return $qur
                            ->searchByField('company_id')
                            ->where('date', '<=', fdate($request->date ?? today()))
                            ->select(DB::Raw('SUM(credit_amount)'));
                    }]);
            }]);
        }])->get();

        $asset = $data['accountGroups']->where('id', 1)->first();
        $liabilities = $data['accountGroups']->where('id', 2)->first();

        $data['asset'][0] = 0;
        $data['asset'][1] = 0;

        foreach ($asset->accountControls as $key => $accountControl) {

            $data['asset'][$key] = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
        }

        $data['liabilities'][0] = 0;
        $data['liabilities'][1] = 0;

        foreach ($liabilities->accountControls as $key => $accountControl) {

            $data['liabilities'][$key] = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
        }

        if(request('export_type')){

            $data['paginate']           = 0;

            return $this->export_service->exportData($data, 'reports/cash-flow/export/', 'Cash Flow List');

        }


        $view = 'reports.cash-flow.' . ($request->print ? 'print' : 'index');

        return view($view, $data);
    }


    /*
     |--------------------------------------------------------------------------
     | REVENUE ANALYSIS  (restores the /reports/revenue-analysis route)
     |--------------------------------------------------------------------------
    */
    public function revenueAnalysisReport(Request $request)
    {
        $this->hasAccess("account.revenue.analysis.reports");

        $from = $request->from ?? date('Y-01-01');
        $to   = $request->to ?? date('Y-m-d');

        $base = Transaction::query()
            ->whereHas('account', function ($q) {
                $q->where('account_group_id', 4)->where('balance_type', 'Credit');
            })
            ->where('date', '>=', $from)
            ->where('date', '<=', $to);

        $data                  = $this->dataService->getAccountData(['accountControls', 'accountSubsidiaries']);
        $data['from']          = $from;
        $data['to']            = $to;
        $data['total_amount']  = (clone $base)->sum('amount');
        $data['row_count']     = (clone $base)->count();

        $base = $base->with(['account.accountSubsidiary', 'account.accountControl'])->orderByDesc('date');

        $data['transaction_items'] = $request->print ? $base->get() : $base->paginate(30)->withQueryString();

        return view('reports.revenue-analysis.index', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | NOMINAL ACCOUNT LEDGER  (all revenue + expense accounts in one ledger)
     |--------------------------------------------------------------------------
    */
    public function nominalAccountLedgerReport(Request $request)
    {
        $this->hasAccess("account.nominal.account.ledger.reports");

        $from = $request->from ?? date('Y-01-01');
        $to   = $request->to ?? date('Y-m-d');

        $base = Transaction::query()
            ->whereHas('account', function ($q) {
                $q->whereIn('account_group_id', [4, 5]);
            })
            ->where('date', '>=', $from)
            ->where('date', '<=', $to);

        $data                 = [];
        $data['from']         = $from;
        $data['to']           = $to;
        $data['debit_total']  = (clone $base)->sum('debit_amount');
        $data['credit_total'] = (clone $base)->sum('credit_amount');

        $base = $base->with(['account.accountSubsidiary', 'account.accountControl'])->orderBy('date');

        $data['transaction_items'] = $request->print ? $base->get() : $base->paginate(100)->withQueryString();

        return view('reports.nominal-account-ledger.index', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | RATIO ANALYSIS (simple financial ratios from posted transactions)
     |--------------------------------------------------------------------------
    */
    public function ratioAnalysisReport(Request $request)
    {
        $this->hasAccess("account.ratio.analysis.reports");

        $from = $request->from ?? date('Y-01-01');
        $to   = $request->to ?? date('Y-m-d');

        $groupTotals = DB::table('transactions')
            ->join('accounts', 'accounts.id', '=', 'transactions.account_id')
            ->where('transactions.date', '>=', $from)
            ->where('transactions.date', '<=', $to)
            ->selectRaw('accounts.account_group_id as group_id, SUM(transactions.amount) as total')
            ->groupBy('accounts.account_group_id')
            ->pluck('total', 'group_id');

        $assets      = (float) ($groupTotals[1] ?? 0);
        $liabilities = (float) ($groupTotals[2] ?? 0);
        $equity      = (float) ($groupTotals[3] ?? 0);
        $revenue     = (float) ($groupTotals[4] ?? 0);
        $expense     = (float) ($groupTotals[5] ?? 0);

        $netProfit = $revenue - $expense;

        $ratios = [
            ['Debt to Equity',            $equity != 0    ? round($liabilities / $equity, 2) : 'n/a',   'Total Liabilities / Owner Equity'],
            ['Debt to Assets',            $assets != 0    ? round($liabilities / $assets, 2) : 'n/a',    'Total Liabilities / Total Assets'],
            ['Net Profit Margin (%)',     $revenue != 0   ? round($netProfit / $revenue * 100, 2) : 'n/a','Net Profit / Revenue'],
            ['Expense Coverage (x)',      $expense != 0   ? round($revenue / $expense, 2) : 'n/a',       'Revenue / Expenses'],
            ['Net Profit',                round($netProfit, 2),                                          'Revenue - Expenses'],
            ['Return on Assets (%)',       $assets != 0   ? round($netProfit / $assets * 100, 2) : 'n/a','Net Profit / Total Assets'],
        ];

        $data = [
            'from'       => $from,
            'to'         => $to,
            'assets'     => $assets,
            'liabilities'=> $liabilities,
            'equity'     => $equity,
            'revenue'    => $revenue,
            'expense'    => $expense,
            'ratios'     => $ratios,
        ];

        return view('reports.ratio-analysis.index', $data);
    }


    /*
     |--------------------------------------------------------------------------
     | RECEIVED PAYMENT STATEMENT (acc_collections listing by date range)
     |--------------------------------------------------------------------------
    */
    public function receivedPaymentStatementReport(Request $request)
    {
        $this->hasAccess("account.received.payment.statement.reports");

        $from = $request->from ?? date('Y-01-01');
        $to   = $request->to ?? date('Y-m-d');

        $base = \Module\Account\Models\Collection::query()
            ->where('date', '>=', $from)
            ->where('date', '<=', $to)
            ->orderByDesc('date');

        $data               = [];
        $data['from']       = $from;
        $data['to']         = $to;
        $data['total']      = (clone $base)->sum('amount');
        $data['customers']  = \Module\Account\Models\Customer::query()->pluck('name', 'id');
        $data['collections'] = $request->print ? $base->get() : $base->paginate(50)->withQueryString();

        return view('reports.received-payment-statement.index', $data);
    }
}
