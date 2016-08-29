<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\WpUser;
use App\Providers\ChampionshipGameComposerProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\PlayerRequest;

class IndividualPlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/individualPlayer');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Player  $indPlayer
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $IndividualPlayer)
    {
        $individualPlayer = $IndividualPlayer->all();
        $id = $individualPlayer["id"];
        unset($individualPlayer["id"]);
        unset($individualPlayer["_token"]);
        unset($individualPlayer["_method"]);
        unset($individualPlayer["team_sort"]);
        unset($individualPlayer["tournament_sort"]);
        unset($individualPlayer["game_sort"]);
        unset($individualPlayer["game_id"]);
        unset($individualPlayer["tournament_id"]);

        $player = new Player();
        $player->name = $individualPlayer["name"];
        $player->username = $individualPlayer["username"];
        $player->email = $individualPlayer["email"];
        $player->phone = $individualPlayer["phone"];
        $player->team_id = $individualPlayer["team_id"];
        $player->updated_on = Carbon::now("CST");
        $player->updated_by = $individualPlayer["team_id"];
        $player->save();
        IndividualPlayer::destroy($id);
        return View::make('game/individualPlayer');

    }


}
