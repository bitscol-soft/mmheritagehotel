<?php

namespace Module\Account\Controllers;

use Illuminate\Http\Request;
use Module\Account\Models\Account;
use Module\Account\Models\AccountControl;
use Module\Account\Models\AccountGroup;
use Module\Account\Models\AccountSubsidiary;

class AccountAjaxController extends Controller
{
    public function getAccountControlsByAccountGroup(Request $request)
    {
        $data = AccountControl::query()
            ->where('account_group_id', $request->account_group_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($data);
    }

    public function getAccountSubsidiariesByAccountControl(Request $request)
    {
        $data = AccountSubsidiary::query()
            ->where('account_control_id', $request->account_control_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($data);
    }

    public function getAccountSubsidiariesAndAccountsByAccountControl(Request $request)
    {
        $data['subsidiaries'] = AccountSubsidiary::query()
            ->where('account_control_id', $request->account_control_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $data['accounts'] = Account::query()
            ->where('account_control_id', $request->account_control_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($data);
    }

    public function getAccountsByAccountControlAndAccountSubsidiary(Request $request)
    {
        $data = Account::query()
            ->where('account_control_id', $request->account_control_id)
            ->where('account_subsidiary_id', $request->account_subsidiary_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($data);
    }
}
