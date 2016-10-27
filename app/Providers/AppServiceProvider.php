<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Rollbar\RollbarServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void // IDE helper
    Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
     */
    public function register()
    {
        // production only providers
        if ($this->app->environment('production')) {
            $this->app->register(RollbarServiceProvider::class);
        }

        // development only providers
        if ($this->app->environment('local')) {
            // IDE helper
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
