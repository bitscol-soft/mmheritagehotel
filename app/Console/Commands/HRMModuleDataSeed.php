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
        if (!file_exists(base_path('module/HRM'))) {
            $this->warn('HRM module is not installed; nothing to do.');
            return 0;
        }
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\DatabaseSeeder'
        ]);

        $this->info('HRM module tables seeded successfully!');
    }
}
