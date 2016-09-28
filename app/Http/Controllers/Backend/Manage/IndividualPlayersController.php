<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Http\Requests\IndividualPlayerRequest;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\Championship\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Traits\Championship\NotifyPlayerTrait;

class IndividualPlayersController extends Controller
{
    use NotifyPlayerTrait;

    public function __construct()
    {
        parent::__construct();
    }

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
     * @param IndividualPlayerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function change(IndividualPlayerRequest $request)
    {
        try {
            $newIndividualPlayer = \DB::transaction(function () use ($request) {
                $newIndividualPlayer = new Player($request->all());
                $newIndividualPlayer->save();
                return $newIndividualPlayer;
            });
        } catch (\Exception $ex) {
            return Redirect::back()->with('error', $ex->getMessage());
        }
        $params = [];
        $params['player'] = $newIndividualPlayer;
        $params['team'] = Team::find($request->input('team_id'));
        $params['tournament'] = Tournament::find($request->input("tournament_sort"));
        $params['game'] = Game::find($request->input("game_id"));
        $hasCreateAnyRelation = PlayerRelationable::createRelation($params);
        if(!$hasCreateAnyRelation){
            return Redirect::back()->withErrors(array('msg'=>trans('individual_player.relation_error')));
        }
        return Redirect('manage/player/edit/'.$newIndividualPlayer->id)->with('success', trans('individual_player.create'));
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
    public function teamMake(Request $request)
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
        $name = "Random-team-".(Team::orderBy('id', 'desc')->first()->id + 1)."- :)";
        $captain = -1;
        $team = new Team();
        $team->name = $name;
        $team->verification_code = PlayerRelationable::generateRandomCode();
        $team->tournament_id = $request['tournament'];
        $team->save();
        foreach ($request->toArray() as $k => $value){
            if(substr($k, 0, 6) == 'player') {
                PlayerRelation::createRelation(["team" => $team->id, 'player' => $value]);
                if($captain < 0){
                    $player = Player::find($value)->first()->toArray();
                    if(isset($player['email']) and $player['email']!='' and $player['email']!=null){
                        $team->captain = $value;
                        $team->save();
                        $captain = $value;
                    }
                }
            }
        }
        return View::make('game/teamMaker');
    }

}
