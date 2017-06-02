<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class UniqueWithValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uniqueWidth', 'App\Http\Controllers\Validator\UniqueWithValidatorController@validateComposeKey');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
