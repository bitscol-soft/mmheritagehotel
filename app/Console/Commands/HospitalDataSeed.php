<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HospitalDataSeed extends Command
{
    protected $signature = 'hospital:seed';

    protected $description = 'Seeds All Hospital Data Tables';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'Module\Hospital\database\seeds\DatabaseSeeder',
        ]);



        $this->info('Hospital data seeded successfully!');
    }
}
