<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Module\Permission\Models\Module;

class MigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


        try {

            $modules = Module::active()->get();


            if ($modules->where('name', 'CRM')->first()) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'CRM' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'HRM')->first() && file_exists(base_path() . '/module/HRM/routes/web_hrm-2.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'HRM' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'General Store')->first() && file_exists(base_path() . '/module/GeneralStore/routes/web_generalstore.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'GeneralStore' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }



            if ($modules->where('name', 'Account & Finance')->first() && file_exists(base_path() . '/module/Account/routes/web_account.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Account' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'POS')->first() && file_exists(base_path() . '/module/PosErp/routes/web_pos_erp.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'PosErp' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'Production')->first() && file_exists(base_path() . '/module/Production/routes/web_production.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Production' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'Hospital')->first() && file_exists(base_path() . '/module/Hospital/routes/web_hospital.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Hospital' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);


                $this->loadViewsFrom(
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Hospital' . DIRECTORY_SEPARATOR . 'views',
                    'hospitals'
                );
            }


            if ($modules->where('name', 'Hotel')->first() && file_exists(base_path() . '/module/Hotel/routes/web_hotel.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Hotel' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'HotelService')->first() && file_exists(base_path() . '/module/HotelService/routes/web_hotel_service.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'HotelService' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }

            // BanquetHall
            if ($modules->where('name', 'BanquetHall')->first() && file_exists(base_path() . '/module/BanquetHall/routes/web_banquet_hall.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'BanquetHall' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }



            if ($modules->where('name', 'HotelWebsite')->first() && file_exists(base_path() . '/module/HotelWebsite/routes/web_hotel_website.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'HotelWebsite' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }



            if ($modules->where('name', 'Restaurant')->first() && file_exists(base_path() . '/module/Restaurant/routes/web_restaurant.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Restaurant' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }


            if ($modules->where('name', 'Bar')->first() && file_exists(base_path() . '/module/Bar/routes/web_bar.php')) {
                $this->loadMigrationsFrom([
                    base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Bar' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                ]);
            }




            /**
             * ---------------------------------------------------------------------
             * Pharmacy Migration
             * ---------------------------------------------------------------------
             */

            if ($modules->where('name', 'Pharmacy')->first() && file_exists(base_path() . '/module/Pharmacy/routes/web_pharmacy.php')) {
                if (Schema::hasTable('hospital_setting_options')) {
                    $this->loadMigrationsFrom([
                        base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Pharmacy' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
                    ]);
                }
            }
        } catch (\Exception $ex) {
        }
    }
}
