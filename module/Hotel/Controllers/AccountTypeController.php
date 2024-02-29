<?php

namespace Module\Hotel\Controllers;

use Exception;
use Illuminate\Http\Request;
use Module\Hotel\Models\AccountType;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\AccountGroup;
use Module\Account\Models\Transaction;
use Module\Account\Services\AccountTransactionService;
use Module\Hotel\Services\StoreAccountService;

class AccountTypeController extends Controller
{

    private $transactionService;
    public $storeAccountService;


    //-------------------------------------------------------------------------//
    //                            CONSTRUCT METHOD                             //
    //-------------------------------------------------------------------------//
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
        $this->storeAccountService  = new StoreAccountService();
    }






    //--------------------------------------------------------------------------//
    //                  INDEX METHOD FOR SHOW ACCOUNT LIST                      //
    //--------------------------------------------------------------------------//
    public function index()
    {
        $this->hasAccess("account.types.index");

        $account = AccountType::where('status',1)->get();
        return view('account_type.index',compact('account'));
    }








    //--------------------------------------------------------------------------//
    //                      EDIT METHOD FOR SHOW EDIT PAGE                      //
    //--------------------------------------------------------------------------//
    public function edit($id)
    {
        $this->hasAccess("account.types.edit");

        $account = AccountType::find($id);
        return view('account_type.edit',compact('account'));
    }









    //--------------------------------------------------------------------------//
    //                  STORE METHOD FOR SAVE (ACCOUNT TYPE)                    //
    //--------------------------------------------------------------------------//
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
        ]);

        try {

            $accountType = AccountType::where('name', $request->name)->first();

            if ($accountType) {
                return redirect()->back()->with('error', 'An Account Type is already exist by this name '.$request->name);
            }
            else{

                DB::transaction(function () use($request){

                    // ACC ACCOUNT CREATE
                    $account_subsidiary_id  = strtolower($request->name) == 'cash' ? 11 : 10;
                    $account                = $this->storeAccountService->storeAccount($request->name, $account_subsidiary_id);

                    // ACCOUNT TYPE CREATE
                    AccountType::create([
                        'account_id' => $account->id,
                        'name'       => $request->name,
                        'status'     => 1,
                        'created_by' => auth()->id()
                    ]);

                });

                return redirect()->route('account-type.index')->with('message', 'Account Type Create Successfull');
            }


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

    }








    //--------------------------------------------------------------------------//
    //                UPDATE METHOD FOR UPDATE (ACCOUNT TYPE)                   //
    //--------------------------------------------------------------------------//
    public function update(Request $request,$id)
    {

        $request->validate([
            'name' => 'required',
        ]);

        try {

            $accountType = AccountType::where('name', $request->name)->where('id', '!=', $id)->first();

            if ($accountType) {
                return redirect()->route('account-type.index')->with('error', 'An Account Type is already exist by this name '.$request->name);
            }
            else{

                DB::transaction(function () use($request, $id){

                    $account_type = AccountType::find($id);

                    // ACC ACCOUNT CREATE
                    if ($account_type->account_id == null) {
                        $account_subsidiary_id  = strtolower($request->name) == 'cash' ? 11 : 10;
                        $account                = $this->storeAccountService->storeAccount($request->name, $account_subsidiary_id);
                    }



                    // ACCOUNT TYPE UPDATE
                    $account_type->update([
                        'account_id'    => $account_type->account_id == null ? $account->id : $account_type->account_id,
                        'name'          => $request->name,
                        'status'        => 1,
                        'updated_by'    => auth()->id()
                    ]);

                });

                return redirect()->route('account-type.index')->with('message', 'Account Type Updated Successfull');

            }


        } catch (\Throwable $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }

    }










    //--------------------------------------------------------------------------//
    //                  DELETE METHOD FOR DELETE ACCOUNT TYPE                   //
    //--------------------------------------------------------------------------//
    public function destroy($id)
    {

        $this->hasAccess("account.types.delete");

        try {

            $account_type = AccountType::find($id);

            if ($account_type->account_id != null) {

                $transaction =  Transaction::where('account_id', $account_type->account_id)->first();

                // CHECKING HAVE ACC TRANSACTION OR NOT
                if($transaction) {

                    return redirect()->back()->with('error', 'Can not delete item while it have transactions');

                }
                // DELETE ACCOUNT
                else{

                    DB::transaction(function () use($account_type) {

                        if ($account_type->account_id != 55) {

                            Account::destroy($account_type->account_id);

                        }

                        $account_type->delete();
                        
                    });
                    return redirect()->back()->with('message', 'Account Type deleted Successfull');
                }

            }
            else {
                // DELETE ACCOUNT TYPE
                $account_type->delete();

                return redirect()->back()->with('message', 'Account Type deleted Successfull');

            }


        } catch (\Throwable $th) {
            return redirect()->back()->withMessage($th->getMessage());
        }


        // DB::transaction(function () use($id) {

        //     $account_type = AccountType::find($id);

        //     if ($account_type->account_id != null) {

        //         $transaction =  Transaction::where(function($q) {
        //                                         $q->where('transaction_item_type', "Account Opening")
        //                                             ->orWhere('transactionable_type', 'Account Opening')
        //                                             ->orWhere('description', 'Account Opening');
        //                                     })->where('account_id', $account_type->account_id)->first();

        //         // DELETE TRANSACTION
        //         if($transaction) {

        //             Transaction::where('transactionable_id', $transaction->transactionable_id)->where('transactionable_type', $transaction->transactionable_type)->where('invoice_no', $transaction->invoice_no)->delete();
        //         }

        //         // DELETE ACCOUNT
        //         Account::destroy($account_type->account_id);

        //     }

        //     // DELETE ACCOUNT TYPE
        //     $account_type->delete();

        // });


    }


}
