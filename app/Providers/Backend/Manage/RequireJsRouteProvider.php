<?php

namespace App\Providers\Backend\Manage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;

class RequireJsRouteProvider extends ServiceProvider
{
    /** @var array $routes Put routes here that should load in assets via require.js */
    protected $routes = ['manage/scores'];
    protected $context = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['game.base'], function ($view) {
            // go though all the routes and check if it's one of the
            // routes that shoud load require.js and atm modules
            for($i=0; $i < count($this->routes); $i++) {
                $route = $this->routes[$i].'*';
                if (Request::is($route)) {
                    $this->context['requireJs'] = true;
                    break;
                }
            }
            // return view with the new context
            $view->with($this->context);
        });
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
