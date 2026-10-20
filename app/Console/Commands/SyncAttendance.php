<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Request;
use Module\HRM\Controllers\AttendanceMachineController;

class SyncAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store Attendance from Finger Print Machine Server';

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
        $request = new Request();
        $attendance = new AttendanceMachineController();
        $attendance->store($request);
        $this->info('Sync Complete');
    }
}
