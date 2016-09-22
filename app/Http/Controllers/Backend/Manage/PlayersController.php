<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\IndividualPlayer;
use App\Models\Championship\Player;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\PlayerRelationable;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\WpUser;
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
//    protected $gamesDBConnection = "";
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
        $player->updated_by =  $this->getUserId();
        $player->updated_on = Carbon::now("CST");
        $player->save();
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
        $pla = $player->getThisPlayerInfoBy();
        return View::make('game/player')->with("thePlayer", $pla);
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
//        dd($player->toArray());
//        array:10 [▼
//          "id" => 1
//          "username" => "Nelson"
//          "email" => "mmm@mmm.com"
//          "name" => "Nels"
//          "phone" => ""
//          "created_at" => null
//          "updated_at" => "2016-09-19 20:55:29"
//          "updated_by" => 5
//          "updated_on" => "2016-09-19 14:55:29"
//          "user_id" => 0
//        ]
//        dd($request->toArray());
//        array:8 [▼
//          "_token" => "0QoUn39tFICoJBmO4nFSXr0uRyeflub2tsanWxVz"
//          "_method" => "PUT"
//          "name" => "Nels"
//          "username" => "Nelson"
//          "email" => "mmm@mmm.com"
//          "phone" => ""
//          "team_id" => "22"
//          "submit" => "Save"
//        ]
        $theTeam = $request->team_id;
        $request =$request->toArray();
        unset($request['_token']);
        unset($request['_method']);
        unset($request['submit']);
        unset($request['team_id']);
        $request['updated_by'] = $this->getUserId();
        $request['updated_on'] = Carbon::now("CST");
        $player->name = $request['name'];
        $player->username = $request['username'];
        $player->email = $request['email'];
        $player->phone = $request['phone'];
        $player->save();
        $playerArray = $player->getThisPlayerInfoBy();

        if($theTeam != $playerArray['team_id']) {
            $this->assignPlayerToTeam($playerArray, $theTeam);
            $playerArray = $player->getThisPlayerInfoBy();
        }

        return View::make('game/player')->with("thePlayer", $playerArray)->with("cont_updated", true);
    }

    /**
     * Remove the specified player from the team and move it to the single player list.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function assignPlayerToTeam($player, $team_id) //todo
    {
        if(Team::find($team_id)->isTeamNotFull()){
            $playerToChange = PlayerRelation::having('player_relations.player_id', '=', $player['player_id'])
                ->having('player_relations.relation_id', '=', $player['team_id'])
                ->having('player_relations.relation_type', '=', PlayerRelationable::getTeamRoute())->first();
            $playerToChange->relation_id = $team_id;
            $playerToChange->save();
        }else{
            return Redirect::back()->withErrors(array('msg'=>'The team has the maximum amount of players. Please pick a different team.'));
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  Player  $player, Team $team
     * @return \Illuminate\Http\Response
     */
    public function remove(Player $player, Team $team)
    {
        PlayerRelation::where('player_id', '=', $player->getRouteKey())
            ->where('relation_id', '=', $team->getRouteKey())
            ->where('relation_type', '=', Team::class)
            ->delete();
        return Redirect::back();
    }
    /**
     * Destroy the specified resource from storage.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        PlayerRelation::where('player_id', '=', $player->getRouteKey())->delete();
        $player->where('id', $player->getRouteKey())->delete();
        return Redirect::back();
    }
    /**
     * Display the specified resource.
     *
     * @param  Request $ids
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $ids)
    {
        $filterArray = [];
        if(isset($ids->team_sort) and trim($ids->team_sort) != "" and trim($ids->team_sort) != "---" and $ids->team_sort!=[]) {
            $filterArray['team'] = trim($ids->team_sort);
        }
        if(isset($ids->tournament_sort) and trim($ids->tournament_sort) != "" and trim($ids->tournament_sort) != "---" and $ids->tournament_sort!=[]) {
            $filterArray['tournament'] = trim($ids->tournament_sort);
        }
        if(isset($ids->game_sort) and trim($ids->game_sort) != "" and trim($ids->game_sort) != "---" and $ids->game_sort!=[]) {
            $filterArray['game'] = trim($ids->game_sort);
        }
        $players = new Player();
        $playerList = $players->getPlayersInfoBy($filterArray);
        return View::make('game/player')->with("players_filter", $playerList)->with('sorts',$ids);

    }
}
