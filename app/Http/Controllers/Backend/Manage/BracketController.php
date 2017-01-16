<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\View;

class BracketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tournament = Tournament::all()->toArray();
        return View::make('game/bracket')->withTournaments($tournament);
    }
    /**
     * Display a listing of the resource.
     *
     * @return null
     */
    public function getTournaments()
    {
//        $tournament = Tournament::all()->toArray();
//
//        return response()
//            ->json();
        return null;
    }
    /**
     * Display a listing of the resource.
     *
     * @return null
     */
    public function getTournament()
    {
//        $tournament = Tournament::all()->toArray();
//
//        return response()
//            ->json();
        return null;
    }
    /**
     * Display a listing of the resource.
     *
     * @return null
     */
    public function save()
    {

//        return response()
//            ->json();
        return null;
    }
}
