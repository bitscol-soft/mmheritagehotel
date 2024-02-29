<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('project-deploy', function () {

    $this->warn('Start Deploying...');

    Artisan::call("migrate:fresh");

    $this->info('Migration Data Preparing...');

    $start_time = now();

    Artisan::call("db:seed");

    Artisan::call('migrate');

    $this->info('Module migration success!');

    $seed_path = 'Module\Permission\database\seeds\DatabaseSeeder';

    Artisan::call('db:seed', [
        '--class' => $seed_path
    ]);

    $time = Carbon::parse(now())->diffInSeconds($start_time);

    $this->warn('Permission Seed success! ' . $time . ' seconds');
    
    Artisan::call('migrate');

    $this->info('Module migration success!');

    // Artisan::call('hotel:seed');

    // $this->info('Hotel data seed success!');

    // Artisan::call('website:seed');

    // $this->info('Website data seed success!');


    Artisan::call('acc:seed');

    $this->info('Account data seed success!');

    Artisan::call('db:seed', [
        '--class' => 'Module\Permission\database\seeds\ModuleAccountTableSeeder'
    ]);

    $this->info('Hotel data integration to Account module success!');
    // 

    $this->info('Project Successfully Deployed!');
});

Artisan::command('hotel-integrate-acc', function () {

    Artisan::call('db:seed', [
        '--class' => 'Module\Permission\database\seeds\ModuleAccountTableSeeder'
    ]);

    $this->info('Hotel data integration to Account module success!');
    // 
});
