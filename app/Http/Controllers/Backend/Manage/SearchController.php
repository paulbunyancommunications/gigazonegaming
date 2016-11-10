<?php

namespace App\Http\Controllers\Backend\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    /**
     * @param Request $request
     * @return mixed
     */
    public function doSearch(Request $request)
    {
        if ($request->ajax()) {
            $response = new \stdClass();
            $response->result = \View::make('game.partials.search')->render();
            return \Response::json($response);
        }
        return \View::make('game.partials.search');
    }
}
