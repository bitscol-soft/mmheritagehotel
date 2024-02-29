<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Module\Account\Models\AccountSubsidiary;
use Module\Account\Services\DataService;
use Module\Account\Services\IndexDataService;

class AccountSubsidiaryController extends Controller
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
        $this->hasAccess("account-subsidiaries.index");

        $data['accountSubsidiaries'] = $this->indexService->getAccountSubsidiaryData();

        return view('setup.account-subsidiaries.index', $data);
    }

    public function create()
    {
        $this->hasAccess("account-subsidiaries.create");

        $data = $this->dataService->getAccountData(['accountGroups']);

        $data['accountControls'] = [];

        return view('setup.account-subsidiaries.create', $data);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->hasAccess("account-subsidiaries.create");

        AccountSubsidiary::query()->create([
            'account_group_id' => $request->account_group_id,
            'account_control_id' => $request->account_control_id,
            'name' => $request->name
        ]);

        return redirect()->route('account-subsidiaries.index')->with('message', 'Account Subsidiary Create Successful');
    }

    public function edit(AccountSubsidiary $accountSubsidiary)
    {
        $this->hasAccess("account-subsidiaries.edit");

        $data = $this->dataService->getAccountData(['accountGroups', 'accountControls']);

        return view('setup.account-subsidiaries.edit', compact('accountSubsidiary'), $data);
    }

    public function update(Request $request, $id)
    {
        $this->hasAccess("account-subsidiaries.edit");


        AccountSubsidiary::query()->where('id', $id)->update([
            'account_group_id' => $request->account_group_id,
            'account_control_id' => $request->account_control_id,
            'name' => $request->name,
            'status' => $request->status
        ]);

        return redirect()->route('account-subsidiaries.index')->with('message', 'Account Subsidiary Update Successful');
    }

    public function destroy($id): RedirectResponse
    {
        $this->hasAccess("account-subsidiaries.delete");

        AccountSubsidiary::query()->where('id', $id)->delete();

        return redirect()->route('account-subsidiaries.index')->with('message', 'Account Subsidiary Delete Successful');
    }
}
