<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Reset boot event listeners in model
     * https://github.com/laravel/framework/issues/1181#issuecomment-51627220
     *
     * @param $model
     */
    public function resetEventListeners($model)
    {
        $model::flushEventListeners();
        $model::boot();
    }
}
