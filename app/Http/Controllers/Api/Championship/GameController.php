<?php

namespace App\Http\Controllers\Api\Championship;

use App\Models\Game;
use Illuminate\Http\Request;

use App\Http\Requests;

class GameController extends Controller
{
    public function index()
    {
        return Game::all();
    }

    public function getGame(Game $game)
    {
        return Game::findOrFail($game);
    }
}
