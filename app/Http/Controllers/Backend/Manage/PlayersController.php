<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\Players_Teams;
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

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/player');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function create(PlayerRequest $request)
    {
        $player = new Player();
        $player->username = $request['username'];
        $player->email = $request['email'];
        $player->name = $request['name'];
        $player->phone = $request['phone'];
        $player->team_id = $request['team_id'];
        $player->updated_by =  $this->getUserId();
        $player->updated_on = Carbon::now("CST");
        $player->save();
//        dd($toUpdate);
//        dd("passed request");
//        $request->save('id', $request->getRouteKey())->update(
////        Player::where('id', $player->getRouteKey())->update(
//            $toUpdate
//        );
//        return View::make('player/player')->with("players", $this->retrievePlayers())->with("thePlayer", $player->where('id', $player->getRouteKey())->first())->with("cont_updated", true);
//        $player->save();
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Player  $player
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Player $player)
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
//        Player::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return View::make('game/player')->with("thePlayer", $player);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        return View::make('game/player')->with("thePlayer", $player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerRequest $request, Player $player) //have to update it to my request
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
        $player->where('id', $player->getRouteKey())->update(
//        Player::where('id', $player->getRouteKey())->update(
            $toUpdate
        );

        $this->DBProcessCachePlayersForced();
        return View::make('game/player')->with("thePlayer", $player->where('id', $player->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified player from the team and move it to the single player list.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function move(Player $player)
    {
        $te = Team::where('id',$player['team_id'])->select('tournament_id')->first();
        $to = Tournament::where('id', $te['tournament_id'])->select('game_id')->first();
        unset($player['team_id']);
        unset($player['id']);
        $player['game_id'] = $to->game_id;
//            Cache::forget('team_c');
//                dd($player);
        IndividualPlayer::create($player);

        Player::where("id", $player->getRouteKey())->delete();
        return redirect('/manage/player');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->where('id', $player->getRouteKey())->delete();
        return redirect('/manage/player');
    }
    /**
     * Display the specified resource.
     *
     * @param  Request $ids
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $ids)
    {

        if(trim($ids->team_sort) != "" and trim($ids->team_sort) != "---" and $ids->team_sort!=[]) {
            if(is_numeric($ids->team_sort)){
                $tourn = trim($ids->team_sort);
            }else {
                $tourn = "%" . trim($ids->team_sort) . "%";
            }
            $players = Player::
            join('players_teams', 'players.id', '=', 'players_teams.player_id')
                ->join('teams','teams.id','=', 'players_teams.team_id')
                ->join('players_tournaments','players.id','=', 'players_tournaments.player_id')
                ->join('tournaments','tournaments.id','=', 'players_tournaments.tournament_id')
                ->join('games','games.id','=', 'tournaments.game_id')
                ->where('teams.id', 'like', $tourn)
                ->orWhere('teams.name', 'like', $tourn)
                ->select(
                    'players.id',
                    'players.email',
                    'players.username',
                    'players.name',
                    'players.phone',
                    'players_teams.verification_code as verification_code',
                    'players_teams.team_id as team_id',
                    'teams.name as team_name',
                    'teams.captain as captain',
                    'tournaments.id as tournament_id',
                    'tournaments.name as tournament_name',
                    'tournaments.max_players as max_players',
                    'tournaments.max_players as max_number_of_players',
                    'games.id as game_id',
                    'games.name as game_name'
                )
                ->orderBy('team_id')
                ->get()
                ->toArray();




        }elseif(trim($ids->tournament_sort) != "" and trim($ids->tournament_sort) != "---" and $ids->tournament_sort!=[]) {

            if(is_numeric($ids->tournament_sort)){
                $tourn = trim($ids->tournament_sort);
            }else {
                $tourn = "%" . trim($ids->tournament_sort) . "%";
            }
            $players = Player::
            join('players_teams', 'players.id', '=', 'players_teams.player_id')
                ->join('teams','teams.id','=', 'players_teams.team_id')
                ->join('players_tournaments','players.id','=', 'players_tournaments.player_id')
                ->join('tournaments','tournaments.id','=', 'players_tournaments.tournament_id')
                ->join('games','games.id','=', 'tournaments.game_id')
                ->where('tournaments.id', 'like', $tourn)
                ->orWhere('tournaments.name', 'like', $tourn)
                ->select(
                    'players.id',
                    'players.email',
                    'players.username',
                    'players.name',
                    'players.phone',
                    'players_teams.verification_code as verification_code',
                    'players_teams.team_id as team_id',
                    'teams.name as team_name',
                    'teams.captain as captain',
                    'tournaments.id as tournament_id',
                    'tournaments.name as tournament_name',
                    'tournaments.max_players as max_players',
                    'tournaments.max_players as max_number_of_players',
                    'games.id as game_id',
                    'games.name as game_name'
                )
                ->orderBy('team_id')
                ->get()
                ->toArray();

        }elseif(trim($ids->game_sort) != "" and trim($ids->game_sort) != "---" and $ids->game_sort!=[]) {

            if(is_numeric($ids->game_sort)){
                $tourn = trim($ids->game_sort);
            }else {
                $tourn = "%" . trim($ids->game_sort) . "%";
            }

            $players = Player::
            join('players_teams', 'players.id', '=', 'players_teams.player_id')
                ->join('teams','teams.id','=', 'players_teams.team_id')
                ->join('players_tournaments','players.id','=', 'players_tournaments.player_id')
                ->join('tournaments','tournaments.id','=', 'players_tournaments.tournament_id')
                ->join('games','games.id','=', 'tournaments.game_id')
                ->where('games.id', 'like', $tourn)
                ->orWhere('games.name', 'like', $tourn)
                ->select(
                    'players.id',
                    'players.email',
                    'players.username',
                    'players.name',
                    'players.phone',
                    'players_teams.verification_code as verification_code',
                    'players_teams.team_id as team_id',
                    'teams.name as team_name',
                    'teams.captain as captain',
                    'tournaments.id as tournament_id',
                    'tournaments.name as tournament_name',
                    'tournaments.max_players as max_players',
                    'tournaments.max_players as max_number_of_players',
                    'games.id as game_id',
                    'games.name as game_name'
                )
                ->orderBy('team_id')
                ->get()
                ->toArray();

        }else {
            $players = Player::
            join('players_teams', 'players.id', '=', 'players_teams.player_id')
                ->join('teams','teams.id','=', 'players_teams.team_id')
                ->join('players_tournaments','players.id','=', 'players_tournaments.player_id')
                ->join('tournaments','tournaments.id','=', 'players_tournaments.tournament_id')
                ->join('games','games.id','=', 'tournaments.game_id')
                ->select(
                    'players.id',
                    'players.email',
                    'players.username',
                    'players.name',
                    'players.phone',
                    'players_teams.verification_code as verification_code',
                    'players_teams.team_id as team_id',
                    'teams.name as team_name',
                    'teams.captain as captain',
                    'tournaments.id as tournament_id',
                    'tournaments.name as tournament_name',
                    'tournaments.max_players as max_players',
                    'tournaments.max_players as max_number_of_players',
                    'games.id as game_id',
                    'games.name as game_name'
                )
                ->orderBy('team_id')
                ->get()
                ->toArray();
        }

        $times = Players_Teams::select(DB::raw("COUNT(team_id) as team_count"), "team_id")->groupBy('team_id')->get()->toArray();
//        dd($times);
        foreach ($players as $key => $player) {
            foreach ($times as $k => $t) {
                if ($player['team_id'] == $t['team_id']) {
                    $players[$key]['team_count'] = $t['team_count'];
                    break;
                }
            }
        }
        return View::make('game/player')->with("players_filter", $players)->with('sorts',$ids);

    }
}
