<?php

namespace Module\Account\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Module\Account\Models\Account;
use Module\Account\Models\AccountControl;
use Module\Account\Models\AccountSubsidiary;
use Module\Account\Models\FundTransfer;
use Module\Account\Models\Voucher;

class IndexDataService
{
    public function getAccountControlData()
    {
        return AccountControl::query()
            ->with('accountGroup')
            ->orderBy('account_group_id')
            ->orderBy('name')
            ->get();
    }

    public function getAccountSubsidiaryData()
    {
        return AccountSubsidiary::query()
            ->with('accountGroup', 'accountControl')
            ->orderBy('account_group_id')
            ->orderBy('account_control_id')
            ->orderBy('name')
            ->get();
    }

    public function getAccountData()
    {
        return Account::query()
            ->with('accountGroup', 'accountControl', 'accountSubsidiary', 'accountType')
            ->orderBy('account_group_id')
            ->orderBy('account_control_id')
            ->orderBy('account_subsidiary_id')
            ->orderBy('name')
            ->get();
    }

    public function getVoucherData($data): LengthAwarePaginator
    {
        return Voucher::query()
            ->where('voucher_type', $data)
            ->orderBy('id', 'DESC')
            ->paginate(30);
    }

    public function getFundTransferData(): LengthAwarePaginator
    {
        return FundTransfer::query()
            ->orderByDesc('id')
            ->orderBy('date', 'DESC')
            ->paginate(30);
    }
}
