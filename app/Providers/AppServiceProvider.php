<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Module\Permission\Models\Module;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Module\Permission\Models\EmployeePermission;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {

            // dd(session()->get('menu_modules'));
            if (Auth::check()) {
                $modules = Module::active()->orderBy('rank')->get();

                session()->put('menu_modules', $modules);
                // session()->forget('slugs');
                if (!session()->has('slugs')) {
                    session()->put('slugs', auth()->user()->permissions()->pluck('slug')->toArray());
                    session()->put('active_modules', $modules->pluck('name')->toArray());
                    $em_slugs = Schema::hasTable('employee_permissions') ? (EmployeePermission::with('permission')->get()->pluck('permission.slug')->toArray() ?? []) : [];
                    session()->put('em_slugs', $em_slugs);
                }


                // share data
                view()->share([
                    'slugs'             => session()->get('slugs') ?? [],
                    'active_modules'    => $modules->pluck('name')->toArray() ?? [],
                    'em_slugs'          => session()->get('em_slugs') ?? []
                ]);


                // forget or remove permission data
                if ($view->getName() == 'partials._footer') {
                    session()->forget('slugs');
                    session()->forget('em_slugs');
                    session()->forget('active_modules');
                }
            } else {
                view()->share(['slugs' => [], 'active_modules' => []]);
            }
        });


        Schema::defaultStringLength(191);
        // Model::preventLazyLoading(! $this->app->isProduction());



        Blade::directive('noTableRecordsFound', function () {
            return '<tr class="bg-danger">
                <th class="text-center text-danger" style="font-size: 18px;line-height:50px" colspan="30">
                    <i class="fa fa-exclamation-triangle"></i> No Data Found
                </th>
            </tr>';
        });

    }
}
