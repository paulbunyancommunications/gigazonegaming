<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Game;
use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Players_Teams;
use App\Models\Championship\Players_Tournaments;
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
    public function create(TeamRequest $request)
    {
        $team = new Team();
        $team->tournament_id = $request['tournament_id'];
        $team->name = $request['name'];
        $team->emblem = $request['emblem'];
        $team->updated_by =  $this->getUserId();
        $team->updated_on = Carbon::now("CST");
        $team->save();
        Cache::forget('teams_c');
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Team  $team
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Team $team)
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
//        dd($team);
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
//        dd($toUpdate);
//        dd("passed request");
        $team->where('id', $team->getRouteKey())->update(
//        Team::where('id', $team->getRouteKey())->update(
            $toUpdate
        );
        Cache::forget('teams_c');
        return View::make('game/team')->with("theTeam", $team->where('id', $team->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy_soft(Team $team)
    {
        $players = Player::
            join("players_teams",'players.id','=','players_teams.player_id')
            ->where("team_id", $team->getRouteKey())
                ->get()
                ->toArray();
//        dd($players);
        foreach($players as $player){
            Players_Teams::where("team_id", $team->getRouteKey())->where('player_id', '=', $player['player_id'])->delete();
        }
        Team::where("id", $team->getRouteKey())->delete();
        Cache::flush();
        return redirect('/manage/team');
    }
//
    /**
     * Remove the specified resource from storage.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy_hard(Team $team)
    {
        $players = Player::
        join("players_teams",'players.id','=','players_teams.player_id')
            ->where("team_id", $team->getRouteKey())
            ->get()
            ->toArray();
        foreach($players as $player){
            Players_Teams::where("team_id", $team->getRouteKey())->where('player_id', '=', $player['player_id'])->delete();
            Player::where('id', '=', $player['player_id'])->delete();
            Players_Tournaments::where('player_id', '=', $player['player_id'])->delete();
        }
        Team::where("id", $team->getRouteKey())->delete();
        Cache::flush();
        return redirect('/manage/team');
    }
    /**
     * Display the specified resource.
     *
     * @param  Request $ids
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $ids)
    {
        if(trim($ids->tournament_sort) != "" and trim($ids->tournament_sort) != "---" and $ids->tournament_sort!=[]) {
            if(is_numeric($ids->tournament_sort)){
                $tourn = trim($ids->tournament_sort);
            }else {
                $tourn = "%" . trim($ids->tournament_sort) . "%";
            }

            $teams =  Team::join('tournaments', 'tournaments.id', '=', 'teams.tournament_id')
                ->join('games', 'games.id', '=', 'tournaments.game_id')
                ->where('tournaments.id', 'like', $tourn)
                ->orWhere('tournaments.name', 'like', $tourn)
                ->select(['teams.id as id','teams.name as name','teams.emblem as emblem','teams.tournament_id as tournament_id', 'tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.id as tournament_id','games.name as game_name'])
                ->groupBy('id')
                ->get()
                ->toArray();
        }elseif(trim($ids->game_sort) != "" and trim($ids->game_sort) != "---" and $ids->game_sort!=[]) {
            if(is_numeric($ids->game_sort)){
                $tourn = trim($ids->game_sort);
            }else {
                $tourn = "%" . trim($ids->game_sort) . "%";
            }
            $teams =  Team::join('tournaments', 'tournaments.id', '=', 'teams.tournament_id')
                ->join('games', 'games.id', '=', 'tournaments.game_id')
                ->where('games.id', 'like', $tourn)
                ->orWhere('games.name', 'like', $tourn)
                ->select(['teams.id as id','teams.name as name','teams.emblem as emblem','teams.tournament_id as tournament_id', 'tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.id as tournament_id','games.name as game_name'])
                ->groupBy('id')
                ->get()
                ->toArray();
        }else {
            $teams = Team::join('tournaments', 'tournaments.id', '=', 'teams.tournament_id')
                ->join('games', 'games.id', '=', 'tournaments.game_id')
                ->select(['teams.id as id', 'teams.name as name', 'teams.emblem as emblem', 'teams.tournament_id as tournament_id', 'tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.id as tournament_id', 'games.name as game_name'])
                ->groupBy('id')
                ->get()
                ->toArray();
        }

        $times = Players_Teams::select(DB::raw("COUNT(team_id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();
        foreach ($teams as $key => $team) {
            foreach ($times as $k => $t) {
                if ($team['id'] == $t['team_id']) {
                    $teams[$key]['team_count'] = $t['team_count'];
                    break;
                }
            }
        }
//        dd($teams);
        return View::make('game/team')->with("teams_filter", $teams)->with('sorts',$ids);
    }

}
