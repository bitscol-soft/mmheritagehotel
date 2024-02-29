<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class AccountSeed extends Command
{
    protected $signature = 'acc:seed';

    protected $description = 'Seeds Accounting Related Tables';

    public function handle()
    {

        Artisan::call('db:seed', [
            '--class' => 'Module\Account\database\seeds\DatabaseSeeder'
        ]);

        $this->info('Account tables seeded successfully!');
    }
}
