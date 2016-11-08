<?php

namespace App\Facades\Backend\Manage;

use Illuminate\Support\Facades\Facade;

class ChampionshipSearch extends Facade {
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ChampionshipSearch';
    }
}