<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HRMModuleDataSeed extends Command
{
    protected $signature = 'hrm:seed';

    protected $description = 'Seeds All HRM Module Tables';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\DatabaseSeeder'
        ]);

        $this->info('HRM module tables seeded successfully!');
    }
}
