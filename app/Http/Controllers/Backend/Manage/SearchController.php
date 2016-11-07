<?php

namespace App\Http\Controllers\Backend\Manage;

use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Search class for when the search function from the head is triggered
 * Search will go though all backend models and return results
 *
 * Class SearchController
 * @package App\Http\Controllers\Backend\Manage
 */
class SearchController extends Controller
{
    public function doSearch(Request $request)
    {
        return View::make('game.partials.search');
    }
}
