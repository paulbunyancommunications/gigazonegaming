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

use App\Models\Championship\Game;
use Illuminate\Http\Request;

use App\Http\Requests;

class GamesController extends \App\Http\Controllers\Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Game $game)
    {
        return $game;
    }
}
