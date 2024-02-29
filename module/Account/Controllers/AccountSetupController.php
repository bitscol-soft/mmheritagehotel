<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Module\Account\Models\AccountSetup;

class AccountSetupController extends Controller
{
    use CheckPermission;

    public function index()
    {
        $this->hasAccess("account.setups.index");

        $data['data'] = AccountSetup::query()->orderBy('name')->get();

        return view('setup.account-setups.index', $data);
    }
}
