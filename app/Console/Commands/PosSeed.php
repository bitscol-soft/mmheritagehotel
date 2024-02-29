<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PosSeed extends Command
{
    protected $signature = 'pos:seed';

    protected $description = 'Seeds POS Related Tables';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'Module\PosErp\database\seeds\DatabaseSeeder'
        ]);

        $this->info('Pos Erp tables seeded successfully!');
    }
}
