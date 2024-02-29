<?php

namespace Module\Bar\Providers;

use Illuminate\Support\ServiceProvider;

class BarServiceProvider extends ServiceProvider
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

        $this->loadMigrationsFrom([
            base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Bar' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
        ]);
    }
}
