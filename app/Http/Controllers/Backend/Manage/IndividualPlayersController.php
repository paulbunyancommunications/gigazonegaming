<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Relation\PlayerRelationable;
use App\Models\Championship\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

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
     * @param Request $IndividualPlayer
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
     * @param Player $player
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function teamFill(Request $request)
    {

        DB::transaction( function () use ($request) {
            $team = $request->team;
            $teamName = Team::where('id', $team)->first()->name;
        foreach ($request->toArray() as $k => $value) {
            if (substr($k, 0, 6) == 'player') {
                PlayerRelation::createRelation(["team" => $team, 'player' => $value]);
            }
        }
            return Redirect::back()->with('success', "The Players had being added to the team $teamName");
            });

        return Redirect::back();

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function teamCreate(Request $request)
    {
        DB::transaction(function () use ($request) {
            $name = "Random-team-" . (Team::orderBy('id', 'desc')->first()->id + 1) . "- :)";
            $captain = -1;
            $team = new Team();
            $team->name = $name;
            $team->verification_code = PlayerRelationable::generateRandomCode();
            $team->tournament_id = $request['tournament'];
            $team->save();
            foreach ($request->toArray() as $k => $value) {
                if (substr($k, 0, 6) == 'player') {
                    PlayerRelation::createRelation(["team" => $team->id, 'player' => $value]);
                    if ($captain < 0) {
                        $player = Player::find($value)->first()->toArray();
                        if (isset($player['email']) and $player['email'] != '' and $player['email'] != null) {
                            $team->captain = $value;
                            $team->save();
                            $captain = $value;
                        }
                    }
                }
            }
            return Redirect::back()->with('success', "The Players had being added to the new team $name");
        });

        return Redirect::back();

    }

}
