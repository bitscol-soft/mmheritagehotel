<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class HotelWebsite extends Command
{
    protected $signature = 'website:seed';

    protected $description = 'Seeds Website Tables';

    public function handle()
    {

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\AboutSectionSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\HotelBannerSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\HotelFeatureSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\HotelFeatureListSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\OurServiceSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\OurServiceListSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\HotelGallerySeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\HotelWebsite\database\seeds\WebsiteSettingSeeder',
        ]);

        $this->info('Hotel Website Module seeded successfully!');
    }
}
