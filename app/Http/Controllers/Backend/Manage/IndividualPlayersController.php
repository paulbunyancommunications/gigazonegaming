<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Tournament;
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
     * @param  all sort  $indPlayer
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $IndividualPlayer)
    {
        $individualPlayer = $IndividualPlayer->all();
        $params = [];
        $params['player'] = $individualPlayer["id"];
        $params['team'] = $individualPlayer["team_id"];
        $params['tournament'] = $individualPlayer["tournament_sort"];
        $params['game'] = $individualPlayer["game_id"];
        $hasCreateAnyRelation = PlayerRelationable::createRelation($params);
        if(!$hasCreateAnyRelation){
            return Redirect::back()->withErrors(array('msg'=>'Sorry no relation was created. The player must already have a relation of such type/s'));

        }

        return View::make('game/individualPlayer');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        PlayerRelation::where('player_id', '=', $player->id)->delete();
        $player->delete();
        return Redirect::back();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teamMake()
    {
        return View::make('game/teamMaker');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teamFill(Request $request)
    {
        $team = $request->team;
        foreach ($request->toArray() as $k => $value){
            if(substr($k, 0, 6) == 'player') {
                PlayerRelation::createRelation(["team" => $team, 'player' => $value]);
            }
        }
        return View::make('game/teamMaker');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function teamCreate(Request $request)
    {
        dd("team creater");
        return Redirect::back();
    }

}
