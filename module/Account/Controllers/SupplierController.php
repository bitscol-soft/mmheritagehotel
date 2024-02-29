<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Module\Account\Models\Account;
use Module\Account\Models\Supplier;
use Module\Account\Services\AccountTransactionService;

class SupplierController extends Controller
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
        $this->hasAccess("account-suppliers.index");

        $suppliers = Supplier::query()->paginate(30);

        return view('party.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $this->hasAccess("account-suppliers.create");

        return view('party.suppliers.create');
    }

    public function store(Request $request)
    {
        $this->hasAccess("account-suppliers.create");


        $request->validate([
            'name'   => 'required',
        ]);

        // try {


            DB::transaction(function () use($request) {
                

                $account = Account::create([

                    'name'                  => $request->name,
                    'account_group_id'      => 2,
                    'account_control_id'    => 3,
                    'account_subsidiary_id' => 4,
                    'opening_balance'       => $request->opening_balance ?? 0,
                    'balance_type'          => 'Credit'
                ]);


                $supplier = Supplier::create([

                    'account_id'        => $account->id,
                    'name'              => $request->name,
                    'mobile'            => $request->mobile,
                    'email'             => $request->email,
                    'address'           => $request->address,
                    'opening_balance'   => $request->opening_balance ?? 0
                ]);


                // $openingAccount = $this->transactionService->getPartyOpeningAccount();



                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $openingAccount,    $supplier->opening_balance, 0,  date('Y-m-d'), 'debit', 'Suppliier Opening Debit', $description = 'Supplier Opening Balance');
                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $account,           0, $supplier->opening_balance,  date('Y-m-d'), 'credit', 'Supplier Opening Credit', $description = 'Supplier Opening Balance');



            });

            return redirect()->route('acc-suppliers.index')->with('message', 'Supplier Create Successful');

        // } catch (Exception $e) {
            
        //     return redirect()->back()->with('error', $e);
        // }
    }

    public function edit($id)
    {
        $this->hasAccess("account-suppliers.edit");

        $supplier = Supplier::query()->find($id);

        return view('party.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->hasAccess("account-suppliers.edit");

        try {

            DB::transaction(function () use($request, $id) {
                    

                $supplier = Supplier::query()->find($id);
                
                
                $supplier->update($request->except('_token', '_method'));

                $supplier->account()->update([

                    'name' => $request->name,
                ]);



                // $openingAccount = $this->transactionService->getPartyOpeningAccount();



                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $openingAccount,     $supplier->opening_balance, 0,  date('Y-m-d'), 'debit', 'Suppliier Opening Debit', $description = 'Supplier Opening Balance');
                // $this->transactionService->storeTransaction($supplier,    'inv-3000' . $supplier->id,    $supplier->account,  0, $supplier->opening_balance,  date('Y-m-d'), 'credit', 'Supplier Opening Credit', $description = 'Supplier Opening Balance');

            });


            return redirect()->route('acc-suppliers.index')->with('message', 'Supplier Update Successful');
            
        } catch (Exception $e) {


            return redirect()->back()->with('error', $e);
        }
    }


    public function destroy($id)
    {

        $this->hasAccess("account-suppliers.delete");

        try {

            DB::transaction(function () use($id) {
                    
                
                $supplier = Supplier::find($id);


                Account::destroy($supplier->account_id);


                Supplier::destroy($id);

            });

            return redirect()->route('acc-suppliers.index')->with('message', 'Supplier Successfully Deleted!');

        } catch (\Exception $ex) {
            
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
