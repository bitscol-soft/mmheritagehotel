<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Module\Permission\Models\Module;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
    public const HOUSE = '/house-keeping';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';


    // module
    protected $generalStore         = 'Module\GeneralStore\Controllers';
    protected $permission           = 'Module\Permission\Controllers';
    protected $hrm                  = '\\';
    protected $account              = '';
    protected $pos                  = 'Module\PosErp\Controllers';
    protected $production           = 'Module\Production\Controllers';
    protected $hospital             = 'Module\Hospital\Controllers';
    protected $crm                  = 'Module\CRM\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        $this->moduleWebRoutes();
        $this->mapHrmApiRoutes();

    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }






    /**
     * -----------------------------------------------------------------------------------------
     * MODULE ROUTE CONFIGURATION
     * -----------------------------------------------------------------------------------------
     */
    protected function moduleWebRoutes()
    {
        try {

            $modules = Module::active()->get();




            // PERMISSION
            Route::group(['middleware' => ['web', 'auth']], function () {
                Route::namespace($this->permission)->group(base_path('module/Permission/routes/web_permission.php'));
            });



            // HRM
            if ($modules->where('name', 'HRM')->first()) {

                if (file_exists(base_path() . '/module/HRM/routes/web_employees.php')) {
                    Route::group(['middleware' => ['web', 'auth']], function () {
                        Route::namespace($this->hrm)->group(base_path('module/HRM/routes/web_employees.php'));
                    });
                }

                if (file_exists(base_path() . '/module/HRM/routes/web_hrm-2.php')) {
                    Route::group(['middleware' => ['web', 'auth']], function () {
                        Route::namespace($this->hrm)->prefix('hrm')->group(base_path('module/HRM/routes/web_hrm-2.php'));
                    });
                }
                if (file_exists(base_path() . '/module/HRM/routes/web.php')) {
                    Route::group(['middleware' => ['web', 'auth']], function () {
                        Route::namespace($this->hrm)->prefix('hrm')->group(base_path('module/HRM/routes/web.php'));
                    });
                }
            }



            // General Store
            if ($modules->where('name', 'General Store')->first() && file_exists(base_path() . '/module/GeneralStore/routes/web_generalstore.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {
                    Route::namespace($this->generalStore)->group(base_path('module/GeneralStore/routes/web_generalstore.php'));
                });
            }





            // CRM
            if ($modules->where('name', 'CRM')->first()) {
                if (file_exists(base_path() . '/module/CRM/routes/web_crm.php')) {
                Route::group(['middleware' => ['web', 'auth']], function () {
                    Route::namespace($this->crm)->group(base_path('module/CRM/routes/web_crm.php'));
                });
            }
            }




            // For Accounting
            if ($modules->where('name', 'Account & Finance')->first() && file_exists(base_path() . '/module/Account/routes/web_account.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {


                    Route::namespace($this->account)->group(base_path('module/Account/routes/web_account.php'));
                });
            }




            // For POS ERP
            if ($modules->where('name', 'POS')->first() && file_exists(base_path() . '/module/PosErp/routes/web_pos_erp.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {


                    Route::namespace($this->pos)->group(base_path('module/PosErp/routes/web_pos_erp.php'));
                });
            }




            // For Production
            if ($modules->where('name', 'Production')->first() && file_exists(base_path() . '/module/Production/routes/web_production.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {


                    Route::namespace($this->production)->group(base_path('module/Production/routes/web_production.php'));
                });
            }



            // Hotel Module Route
            if ($modules->where('name', 'HotelService')->first() && file_exists(base_path() . '/module/HotelService/routes/web_hotel_service.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/HotelService/routes/web_hotel_service.php'));
                });
            }
            // Banquet Hall Route
            if ($modules->where('name', 'BanquetHall')->first() && file_exists(base_path() . '/module/BanquetHall/routes/web_banquet_hall.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/BanquetHall/routes/web_banquet_hall.php'));
                });
            }



            // Hotel
            if ($modules->where('name', 'Hotel')->first() && file_exists(base_path() . '/module/Hotel/routes/web_hotel.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/Hotel/routes/web_hotel.php'));
                });


            }



            // Hotel Website
            if ($modules->where('name', 'HotelWebsite')->first() && file_exists(base_path() . '/module/HotelWebsite/routes/web_hotel_website.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/HotelWebsite/routes/web_hotel_website.php'));

                });
            }





            // Restaurant Module Route
            if ($modules->where('name', 'Restaurant')->first() && file_exists(base_path() . '/module/Restaurant/routes/web_restaurant.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/Restaurant/routes/web_restaurant.php'));

                });

                Route::namespace('\\')->group(base_path('module/Restaurant/routes/web_api.php'));
            }




            // Bar Module Route
            if ($modules->where('name', 'Bar')->first() && file_exists(base_path() . '/module/Bar/routes/web_bar.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/Bar/routes/web_bar.php'));

                });

                Route::namespace('\\')->group(base_path('module/Bar/routes/web_api.php'));

            }




            // Hospital Route
            if ($modules->where('name', 'Hospital')->first() && file_exists(base_path() . '/module/Hospital/routes/web_hospital.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {


                    Route::namespace('\\')->group(base_path('module/Hospital/routes/web_hospital.php'));
                });
            }




            // Pharmacy Route
            if ($modules->where('name', 'Pharmacy')->first() && file_exists(base_path() . '/module/Pharmacy/routes/web_pharmacy.php')) {

                Route::group(['middleware' => ['web', 'auth']], function () {

                    Route::namespace('\\')->group(base_path('module/Pharmacy/routes/web_pharmacy.php'));
                });
            }

        } catch (\Exception $ex) {
            info('Database not setup or permission table not found');
        }
    }







    protected function mapHrmApiRoutes()
    {

        try {

            $modules = Module::active()->get();

            if ($modules->where('name', 'HRM')->first() && file_exists(base_path() . '/module/HRM/web_hrm_api.php')) {
                Route::prefix('api/hrm')
                    ->middleware('api')
                    ->namespace('Module\HRM\Controllers\Api')
                    ->group(base_path('module/HRM/routes/web_hrm_api.php'));
            }
        } catch (\Exception $ex) {
        }
    }
}
