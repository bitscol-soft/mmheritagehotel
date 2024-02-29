<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class HotelSeeder extends Command
{

    protected $signature = 'hotel:seed';

    protected $description = 'Seeds Hotel Module Tables';

    public function handle()
    {

        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\VatSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\AccountTypeSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\AminitiesSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\HotelGuestSeeder',
        ]);


        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\RoomCategoriesSeeder',
        ]);


        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\RoomPhotosSeeder',
        ]);


        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\BookingNotesSeeder',
        ]);


        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\GuestRegistrationTermsSeeder',
        ]);

        Artisan::call('db:seed', [
            '--class' => 'Module\Hotel\database\seeds\BookingPurposeSeeder',
        ]);


        $this->info('Hotel Module seeded successfully!');
    }

}
