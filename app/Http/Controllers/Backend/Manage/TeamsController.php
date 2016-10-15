<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Player_Team;
use App\Models\Championship\Player_Tournament;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Http\Request;

use App\Models\WpUser;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use \Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\TeamRequest;
class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/team');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function store(TeamRequest $request)
    {
        $team = new Team();
        if(isset($request['tournament_id']) and $request['tournament_id']!='---') {
            $team->tournament_id = $request['tournament_id'];
        }else{
            return Redirect::back()->with("errors", "A tournament must be selected");
        }
        $team->name = $request['name'];
        $team->emblem = $request['emblem'];
        $team->updated_by =  $this->getUserId();
        $team->updated_on = Carbon::now("CST");
        $team->verification_code= str_random(8);
        $team->save();
        return redirect('manage/team')->with('success',"The team ".$request['name']." was added");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Team  $team
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Team $team)
    {
        dd("Are you trying to hack us? ip_address:".$_SERVER['REMOTE_ADDR']);
//        $updatedBy = $this->getUserId();
//        $updatedOn = Carbon::now("CST");
//        $toUpdate = array_merge($request->all(), [
//            'updated_by' => $updatedBy,
//            'updated_on' => $updatedOn
//        ] );
//        unset($toUpdate['_token']);
//        unset($toUpdate['_method']);
//        unset($toUpdate['id']);
//        unset($toUpdate['reset']);
//        unset($toUpdate['submit']);
//        Team::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return View::make('game/team')->with("theTeam", $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return View::make('game/team')->with("theTeam", $team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, Team $team) //have to update it to my request
    {
        $updatedBy = $this->getUserId();
        $updatedOn = Carbon::now("CST");
        $toUpdate = array_merge($request->all(), [
            'updated_by' => $updatedBy,
            'updated_on' => $updatedOn
        ] );
        unset($toUpdate['_token']);
        unset($toUpdate['_method']);
        unset($toUpdate['id']);
        unset($toUpdate['reset']);
        unset($toUpdate['submit']);
        $team->where('id', $team->getRouteKey())->update(
            $toUpdate
        );
        return Redirect::back()->with('success',"The team ".$team->fresh()->name." was updated")
            ->with("theTeam", $team);
    }

    /**
     * Remove players from team and delete team
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy_soft(Team $team)
    {
        PlayerRelation::where('relation_id', '=', $team->getRouteKey())->where('relation_type', '=', Team::class)->delete();
        $team->where('id', $team->getRouteKey())->delete();
        return Redirect::back();
    }
//
    /**
     * Remove players from team and delete team
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy_hard(Team $team)
    {
        foreach (PlayerRelation::where('relation_id', '=', $team->getRouteKey())->where('relation_type', '=', Team::class)->get()
            as $k => $relation){
            Player::where('id','=', $relation->player_id)->delete();
            PlayerRelation::where('player_id', '=', $relation->player_id)->delete();
        }
        $team->where('id', $team->getRouteKey())->delete();
        return Redirect::back();
    }
    /**
     * Display the specified resource.
     *
     * @param  Request $ids
     * max is a game and a tournament id
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $ids)
    {
//        dd($ids);
        $tournament = '';
        $game = '';
        if(trim($ids->tournament_sort) != "" and trim($ids->tournament_sort) != "---" and $ids->tournament_sort!=[]) {
            $tournament = intval(trim($ids->tournament_sort));
        }
        elseif(trim($ids->game_sort) != "" and trim($ids->game_sort) != "---" and $ids->game_sort!=[]) {
            $game = intval(trim($ids->game_sort));
        }
//        var_dump($tournament);
//        dd($tournament);
        $teams =  Team::join('tournaments', function($join)use($tournament){
            if($tournament!=''){
                $join->on('tournaments.id', '=', 'teams.tournament_id')
                ->where('tournaments.id', '=', $tournament);
//                ->where('tournaments.id', '=', '');
            }else{
                $join->on('tournaments.id', '=', 'teams.tournament_id');
            }
        })

            ->join('games', function($join)use($game){
                if($game!='') {
                    $join->on('tournaments.game_id', '=', 'games.id')
                    ->where('games.id', '=', $game);
                }else{
                    $join->on('tournaments.game_id', '=', 'games.id');
                }
            })
            ->leftJoin('player_relations', function($join)use($tournament, $game){
                $join->on('player_relations.relation_id', '=', 'teams.id')
                ->where('player_relations.relation_type', '=', Team::class);
        } );
//        dd($teams->get()->toArray());
//        dd($teams->get());
        $teams_cooked = $teams->select(['teams.id as team_id',
            'teams.name as team_name',
            'teams.emblem as team_emblem',
            'teams.captain as team_captain',
            'teams.verification_code as team_verification_code',
            'tournaments.name as tournament_name',
            'tournaments.game_id',
            'tournaments.max_players as team_max_players',
            'tournaments.id as tournament_id',
            'games.name as game_name',
            DB::raw("COUNT(relation_id) as team_count")
        ])->orderBy('team_id')->get()->toArray();
        return View::make('game/team')->with("teams_filter", $teams_cooked)->with('sorts',$ids);
    }

}
