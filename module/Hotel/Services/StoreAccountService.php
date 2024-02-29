<?php

namespace Module\Hotel\Services;

use Module\Account\Models\Account;
use Module\Account\Models\AccountGroup;

class StoreAccountService
{


    //-------------------------------------------------------------------------//
    //                          STORE ACCOUNT METHOD                           //
    //-------------------------------------------------------------------------//
    public function storeAccount($name, $account_subsidiary_id)
    {
        $balance_type = optional(AccountGroup::find(1))->balance_type;

        $accAccount   =  Account::where('name', $name)
                                ->where('account_group_id', 1)
                                ->where('account_control_id', 1)
                                ->where('account_subsidiary_id', $account_subsidiary_id)
                                ->first();

        if ($accAccount) {
            $account = $accAccount;
        } else {
            $account = Account::create([
                'name'                  => $name,
                'account_group_id'      => 1,
                'account_control_id'    => 1,
                'account_subsidiary_id' => $account_subsidiary_id,
                'opening_balance'       => 0,
                'balance_type'          => $balance_type
            ]);
        }

        return $account;

    }

}
