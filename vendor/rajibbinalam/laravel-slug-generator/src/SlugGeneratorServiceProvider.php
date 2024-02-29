<?php

namespace Rajib\LaravelSlugGenerator;

use Illuminate\Support\ServiceProvider;

class SlugGeneratorServiceProvider extends ServiceProvider
{



    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Laravel-Slug-Generator', function($app){
            return new \Rajib\LaravelSlugGenerator\SlugGenerator();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/SlugGenerator.php', 'SlugGenerator'
        );
    }



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/SlugGenerator.php' => config_path('SlugGenerator.php'),
        ]);
    }
}
