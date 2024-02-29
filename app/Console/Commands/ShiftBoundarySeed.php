<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ShiftBoundarySeed extends Command
{
    protected $signature = 'hrm:shift-boundary';

    protected $description = 'Seeds Shift Boundary Table Data';

    public function handle()
    {
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\ShiftBoundaryTableSeeder'
        ]);

        $this->info('Seeds Shift Boundary Table Data!');
    }
}
