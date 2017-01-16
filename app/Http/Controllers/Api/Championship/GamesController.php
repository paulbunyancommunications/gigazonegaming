<?php 
namespace App\Http\Controllers\Api\Championship;

/**
 * GamesController
 *
 * Games Api controller
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Http\Controllers\Api\Championship
 */

use App\Http\Controllers\Controller;
use App\Models\Championship\Game;

class GamesController extends Controller
{
    /**
     * Get all the games
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Game::all();
    }

    /**
     * Try and get Game by Id
     * @param Game $game
     * @return Game
     */
    public function show(Game $game)
    {
        return $game;
    }
}
