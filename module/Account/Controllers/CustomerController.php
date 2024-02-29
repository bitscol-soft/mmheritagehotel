<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\Customer;
use Module\Account\Services\AccountTransactionService;

class CustomerController extends Controller
{
    use CheckPermission;

    private $transactionService;






    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->transactionService   = new AccountTransactionService();
    }


    public function index()
    {
        $this->hasAccess("account-customers.index");

        $customers = Customer::query()->paginate(30);

        return view('party.customers.index', compact('customers'));
    }

    public function create()
    {
        $this->hasAccess("account-customers.create");

        return view('party.customers.create');
    }

    public function store(Request $request)
    {
        $this->hasAccess("account-customers.create");

        $request->validate([
            'name'   => 'required'
        ]);


        try {

            DB::transaction(function () use($request) {
                
                $account = Account::create([

                    'name'                  => $request->name,
                    'account_group_id'      => 1,
                    'account_control_id'    => 1,
                    'account_subsidiary_id' => 8,
                    // 'opening_balance'       => $request->opening_balance ?? 0,
                    'opening_balance'       => 0,
                    'balance_type'          => 'Debit'
                ]);

                $customer = Customer::create([

                    'account_id'        => $account->id,
                    'name'              => $request->name,
                    'mobile'            => $request->mobile,
                    'email'             => $request->email,
                    'address'           => $request->address,
                    'opening_balance'   => $request->opening_balance ?? 0
                ]);

                // $openingAccount = $this->transactionService->getPartyOpeningAccount();


                // $this->transactionService->storeTransaction($customer,    'inv-2000' . $customer->id,    $openingAccount,    0, $customer->opening_balance,  date('Y-m-d'), 'credit', 'Customer Opening Credit', $description = 'Customer Oening Balance');
                // $this->transactionService->storeTransaction($customer,    'inv-2000' . $customer->id,    $account,           $customer->opening_balance, 0,  date('Y-m-d'), 'debit', 'Customer Opening Debit', $description = 'Customer Oening Balance');

            });

            return redirect()->route('acc-customers.index')->with('message', 'Customer Create Successful');

        } catch (Exception $e) {


            return redirect()->back()->with('error', $e);
        }
    }

    public function edit($id)
    {
        $this->hasAccess("account-customers.edit");

        $customer = Customer::query()->find($id);

        return view('party.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $this->hasAccess("account-customers.edit");

        try {

            DB::transaction(function () use($request, $id) {
                
                $customer = Customer::find($id);

                $customer->update($request->except('_token', '_method'));

                $customer->account()->update([

                    'name' => $request->name
                ]);


                // $openingAccount = $this->transactionService->getPartyOpeningAccount();


                // $this->transactionService->storeTransaction($customer,    'inv-2000' . $customer->id,    $openingAccount,       0, $customer->opening_balance,  date('Y-m-d'), 'credit', 'Customer Opening Credit', $description = 'Customer Opening Balance');
                // $this->transactionService->storeTransaction($customer,    'inv-2000' . $customer->id,    $customer->account,    $customer->opening_balance, 0,  date('Y-m-d'), 'debit', 'Customer Opening Debit', $description = 'Customer Opening Balance');

            });


            return redirect()->route('acc-customers.index')->with('message', 'Customer Update Successful');

        } catch (Exception $e) {
            
            return redirect()->back()->with('error', $e);
        }
    }


    public function destroy($id)
    {
        $this->hasAccess("account-customers.delete");

        try {

            DB::transaction(function () use($id) {
                
                
                $customer = Customer::find($id);
                

                Account::destroy($customer->account_id);


                Customer::destroy($id);

            });

            return redirect()->route('acc-customers.index')->with('message', 'Customer Successfully Deleted!');

        } catch (\Exception $ex) {
            
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
