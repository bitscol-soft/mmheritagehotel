<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class HRMEvaluationSeed extends Command
{
    protected $signature = 'hrm-evaluation:seed';

    protected $description = 'Seeds All HRM Module Tables';

    public function handle()
    {
        if (!file_exists(base_path('module/HRM'))) {
            $this->warn('HRM module is not installed; nothing to do.');
            return 0;
        }
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\EvaluationTableSeeder',
        ]);
        
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\EvaluationQuestionTableSeeder'
        ]);
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\SelfEvaluationSetupTableSeeder'
        ]);
        Artisan::call('db:seed', [
            '--class' => 'Module\HRM\database\seeds\SelfEvaluationSetupOptionTableSeeder'
        ]);

        $this->info('HRM module evaluation data seeded successfully!');
    }
}
