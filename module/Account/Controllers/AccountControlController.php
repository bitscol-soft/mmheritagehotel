<?php

namespace Module\Account\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Module\Account\Services\DataService;
use Module\Account\Models\AccountControl;
use Module\Account\Services\IndexDataService;

class AccountControlController extends Controller
{
    use CheckPermission;

    private $dataService;
    private $indexService;




    public function __construct()
    {
        $this->dataService = new DataService();
        $this->indexService = new IndexDataService();
    }




    public function index()
    {
        $this->hasAccess("account-controls.index");

        $data['accountControls'] = $this->indexService->getAccountControlData();

        return view('setup/account-controls/index', $data);
    }




    public function create()
    {
        $this->hasAccess("account-controls.index");

        $data = $this->dataService->getAccountData(['accountGroups']);
        $company = Company::pluck('name', 'id');

        return view('setup.account-controls.create', compact('company'), $data);
    }




    public function store(Request $request): RedirectResponse
    {

        $data = AccountControl::query()->create([
            'account_group_id' => $request->account_group_id,
            'name' => $request->name,
            'status' => 1
        ]);
        
        if($request->company_id != auth()->user()->company_id){
            $data->update([
                'company_id' => $request->company_id,
            ]);
        }


        return redirect()->route('account-controls.index')->with('message', 'Account Control Create Successful');
    }





    public function edit(AccountControl $accountControl)
    {
        $this->hasAccess("account-controls.index");

        $data = $this->dataService->getAccountData(['accountGroups']);

        return view('setup.account-controls.edit', compact('accountControl'), $data);
    }






    public function update(Request $request, $id)
    {

        AccountControl::query()->where('id', $id)->update([
            'account_group_id' => $request->account_group_id,
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('account-controls.index')->with('message', 'Account Control Update Successful');
    }






    public function destroy($id): RedirectResponse
    {
        $this->hasAccess("account-controls.index");

        AccountControl::query()->where('id', $id)->delete();

        return redirect()->route('account-controls.index')->with('message', 'Account Control Delete Successful');
    }
}
