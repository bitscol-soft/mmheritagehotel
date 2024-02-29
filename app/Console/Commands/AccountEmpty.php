<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Account\Models\AccountControl;
use Module\Account\Models\AccountGroup;
use Module\Account\Models\AccountSetup;
use Module\Account\Models\AccountSubsidiary;
use Module\Account\Models\Bank;
use Module\Account\Models\Transaction;
use Module\Account\Models\Voucher;
use Module\Account\Models\VoucherDetail;

class AccountEmpty extends Command
{
    protected $signature = 'acc:empty';

    protected $description = 'Empties Accounting Related Tables';

    public function handle()
    {
        Bank::query()->truncate();
        Voucher::query()->truncate();
        VoucherDetail::query()->truncate();
        Transaction::query()->truncate();
        AccountSubsidiary::query()->truncate();
        AccountControl::query()->truncate();
        AccountSetup::query()->truncate();
        AccountGroup::query()->truncate();
    }
}
