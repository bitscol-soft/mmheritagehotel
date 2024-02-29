<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Module\Account\Models\Account;
use Module\Account\Models\Transaction;
use Module\Account\Services\DataService;
use Module\Account\Services\IndexDataService;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\AccountGroup;
use Module\Account\Services\AccountTransactionService;

class AccountController extends Controller
{
    use CheckPermission;

    private $dataService;
    private $indexService;
    private $transactionService;

    public function __construct()
    {
        $this->dataService          = new DataService();
        $this->indexService         = new IndexDataService();
        $this->transactionService   = new AccountTransactionService();
    }

    public function index()
    {

        $this->hasAccess("account.index");

        $data['accounts'] = $this->indexService->getAccountData();

        return view('setup.accounts.index', $data);
    }

    public function create()
    {
        $this->hasAccess("account.create");

        $data = $this->dataService->getAccountData(['accountGroups']);

        $data['accountControls'] = [];
        $data['accountSubsidiaries'] = [];

        return view('setup.accounts.create', $data);
    }

    public function store(Request $request)
    {
        $this->hasAccess("account.create");

        $balance_type = optional(AccountGroup::find($request->account_group_id))->balance_type;

        $data = [
            'name'                  => $request->name,
            'account_group_id'      => $request->account_group_id,
            'account_control_id'    => $request->account_control_id,
            'account_subsidiary_id' => $request->account_subsidiary_id,
            'opening_balance'       => $request->opening_balance ?? 0,
            'remarks'               => $request->remarks
        ];


        $account = Account::query()->create($data);



        $openingAccount = $this->transactionService->getPartyOpeningAccount();

        if($balance_type == 'Debit') {

            $account_debit  = $account->opening_balance;
            $opening_debit  = 0;

            $account_credit = 0;
            $opening_credit = $account->opening_balance;

            $account_type   = 'debit';
            $oepning_type   = 'credit';

        } else {


            $account_debit  = 0;
            $opening_debit  = $account->opening_balance;

            $account_credit = $account->opening_balance;
            $opening_credit = 0;

            $account_type   = 'credit';
            $oepning_type   = 'debit';
        }

        // $this->transactionService->storeTransaction($account,    'inv-0000' . $account->id,    $account,            $account_debit, $account_credit,  date('Y-m-d'), $account_type, 'Account Opening', $description = 'Account Opening Balance');
        // $this->transactionService->storeTransaction($account,    'inv-0000' . $account->id,    $openingAccount,     $opening_debit, $opening_credit,  date('Y-m-d'), $oepning_type, 'Account Opening', $description = 'Account Opening Balance');



        return redirect()->route('accounts.index')->with('message', 'Account Create Successful');
    }

    public function edit(Account $account)
    {
        $this->hasAccess("account.edit");



        $data = $this->dataService->getAccountData(['accountGroups']);


        $data['transaction_count'] = Transaction::where('transactionable_type', '!=', 'Account Opening')->where('account_id', $account->id)->count();

        $data['accountControls'] = $this->dataService->accountControls($account->account_group_id);

        $data['accountSubsidiaries'] = $this->dataService->accountSubsidiaries($account->account_control_id);

        return view('setup.accounts.edit', compact('account'), $data);
    }

    public function update(Request $request, Account $account)
    {
        $this->hasAccess("account.edit");


        $account->update($request->all());

        $openingAccount = $this->transactionService->getPartyOpeningAccount();

        $balance_type = optional(AccountGroup::find($request->account_group_id))->balance_type;

        if($balance_type == 'Debit') {


            $account_type   = 'debit';
            $oepning_type   = 'credit';

        } else {


            $account_type   = 'credit';
            $oepning_type   = 'debit';
        }


        $transaction =  Transaction::where('transaction_item_type', "Account Opening")->where('account_id', $account->id)->first();

        if($transaction) {

            $transactions = Transaction::where('transactionable_id', $transaction->transactionable_id)->where('transactionable_type', $transaction->transactionable_type)->where('invoice_no', $transaction->invoice_no)->get();

            foreach($transactions as $transaction)
            {
                if($transaction->account_id == $account->id) {

                    $transaction->update([
                        'credit_amount' => ($account_type == 'credit' ? $account->opening_balance : 0),
                        'debit_amount'  => ($account_type == 'debit' ? $account->opening_balance : 0),
                        'balance_type'  => $account_type
                    ]);

                } else {

                    $transaction->update([
                        'credit_amount' => ($oepning_type == 'credit' ? $account->opening_balance : 0),
                        'debit_amount'  => ($oepning_type == 'debit' ? $account->opening_balance : 0),
                        'balance_type'  => $oepning_type
                    ]);

                }
            }
        }


        return redirect()->route('accounts.index')->with('message', 'Account Update Successful');
    }


    public function destroy($id)
    {
        $this->hasAccess("account.delete");

        try {

            DB::transaction(function () use($id) {

                $transaction =  Transaction::where(function($q) {
                    $q->where('transaction_item_type', "Account Opening")
                        ->orWhere('transactionable_type', 'Account Opening')
                        ->orWhere('description', 'Account Opening');
                })->where('account_id', $id)->first();

                if($transaction) {

                    Transaction::where('transactionable_id', $transaction->transactionable_id)->where('transactionable_type', $transaction->transactionable_type)->where('invoice_no', $transaction->invoice_no)->delete();
                }

                Account::destroy($id);

            });


            return redirect()->route('accounts.index')->with('message', 'Account Successfully Deleted!');

        } catch (\Exception $ex) {

            return redirect()->back()->withError($ex->getMessage());
        }
    }
}
