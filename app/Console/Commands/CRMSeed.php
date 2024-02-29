<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CRMSeed extends Command
{
    protected $signature = 'crm:seed';

    protected $description = 'Seeds CRM Related Tables';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'Module\CRM\database\seeds\DatabaseSeeder'
        ]);

        $this->info('CRM tables seeded have been successfully!');
    }
}
