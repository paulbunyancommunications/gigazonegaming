<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\Blade;
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
        Blade::directive('autoVersion', function($expression) {
            return "<?php echo \\Pbc\\AutoVersionSingle::file($expression); ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void // IDE helper
     */
    public function register()
    {
        // production only providers
        if ($this->app->environment() === 'production') {
            // Rollbar service provider
            if(class_exists('Jenssegers\\Rollbar\\RollbarServiceProvider')) {
                $this->app->register(RollbarServiceProvider::class);
            }
        }

        // development only providers
        if ($this->app->environment() === 'local') {
            // IDE helper
            if (class_exists('Barryvdh\\LaravelIdeHelper\\IdeHelperServiceProvider')) {
                $this->app->register(IdeHelperServiceProvider::class);
            }
        }
    }
}
