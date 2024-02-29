<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Module\Account\Models\Account;
use Module\Account\Models\AccountControl;
use Module\Account\Models\AccountGroup;
use Module\Account\Models\AccountOpeningBalance;
use Module\Account\Services\AccountTransactionService;

class AccountOpeningBalanceController extends Controller
{
    use CheckPermission;

    private $transactionService;

    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
    }


    public function create(Request $request)
    {

        $this->hasAccess("account.index");

        $data['companies']          = Company::userCompanies();
        $data['accountGroups']      = AccountGroup::pluck('name', 'id');
        $data['accountControls']    = AccountControl::pluck('name', 'id');
        $data['accounts']           = Account::query()
                                        ->active()
                                        ->searchByField('account_group_id')
                                        ->searchByField('account_control_id')
                                        ->with(['opening_balances' => function($q) use($request) {
                                            $q->where('company_id', $request->company_id);
                                        }])
                                        ->orderBy('name')
                                        ->paginate(15);

        return view('setup.account-opening-balances.create', $data);
    }


    public function store(Request $request)
    {
        foreach ($request->account_ids ?? [] as $key => $account_id) {

            if($request->amounts[$key] <> 0) {
               
                $balance = AccountOpeningBalance::updateOrCreate([

                    'account_id' => $account_id,
                    'company_id' => $request->company_id,
                ], [
                    'amount' => $amount = $request->amounts[$key]
                ]); 


                $account = Account::with('accountGroup')->find($account_id);



                $balance_type = $account->accountGroup->balance_type;

                $openingAccount = $this->transactionService->getPartyOpeningAccount();

                if($balance_type == 'Debit') {

                    $account_debit  = $amount;
                    $opening_debit  = 0;

                    $account_credit = 0;
                    $opening_credit = $amount;

                    $account_type   = 'debit';
                    $oepning_type   = 'credit';

                } else {
                    

                    $account_debit  = 0;
                    $opening_debit  = $amount;

                    $account_credit = $amount;
                    $opening_credit = 0;

                    $account_type   = 'credit';
                    $oepning_type   = 'debit';
                }

                $this->transactionService->storeTransaction($request->company_id, $account,    'inv-0000' . $account->id,    $account,            $account_debit, $account_credit,  date('Y-m-d'), $account_type, 'Account Opening', $description = 'Opening Balance');
                $this->transactionService->storeTransaction($request->company_id, $account,    'inv-0000' . $account->id,    $openingAccount,     $opening_debit, $opening_credit,  date('Y-m-d'), $oepning_type, 'Account Opening', $description = 'Opening Balance');

            }
        }


        return redirect()->back()->withMessage('Opening Balance Successfully Updated');
        
    }
}
