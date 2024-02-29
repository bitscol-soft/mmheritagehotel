<?php

namespace Module\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Module\Account\Models\AccountGroup;

class AccountGroupController extends Controller
{
    use CheckPermission;

    public function index()
    {
        $this->hasAccess("account-groups.index");

        $data['data'] = AccountGroup::query()->orderBy('name')->get();

        return view('setup.account-groups.index', $data);
    }
}
