<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HrSystemSettingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hr_system:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HR System Setting Table Data Seed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(base_path('module/HRM'))) {
            $this->warn('HRM module is not installed; nothing to do.');
            return 0;
        }
        Artisan::call('db:seed', ['--class' => 'Module\HRM\database\seeds\HrSystemSettingTableSeeder']);

        $this->info('HR System Setting Table Data Seed Successfully.');
    }
}
