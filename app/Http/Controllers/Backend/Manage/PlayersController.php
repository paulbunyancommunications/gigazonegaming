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
use Illuminate\Database\Eloquent\Relations\Relation;
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
    public function store(PlayerRequest $request)
    {
        $player = new Player();
        list($request, $theAssociation) = $this->UserCleanUp($request);
        list($playerArray, $success, $errors) = $this->getPlayerInfoAndErrors($request, $player, $theAssociation); //save method for player is in this function call

        if($success!='' and $errors!=''){
            return redirect("manage/player/edit/".$playerArray['id'])
//                ->withInput()
                ->with('success', $success)
                ->with('error', $errors)
                ->with("thePlayer", $playerArray);
        }elseif ($success!=''){
            return redirect("manage/player/edit/".$playerArray['id'])
//                ->withInput()
                ->with('success', $success)
                ->with("thePlayer", $playerArray);
        }elseif ($errors!=''){
            return redirect('manage/player/')
                ->withInput()
                ->with('error', $errors)
                ->with("thePlayer", $playerArray);
        }else{
            return redirect("manage/player/edit/".$playerArray['id'])
                ->with("thePlayer", $playerArray);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Player  $player
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Player $player)
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
        $pla = $player->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames(['Team','Tournament','Game']);
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
        list($request, $theAssociation) = $this->UserCleanUp($request);

        list($playerArray, $success, $errors) = $this->getPlayerInfoAndErrors($request, $player, $theAssociation);
        if($success!='' and $errors!=''){
            return Redirect::back()
                ->withInput()
                ->with('success', $success)
                ->with('error', $errors)
                ->with("thePlayer", $playerArray);
        }elseif ($success!=''){
            return Redirect::back()
                ->withInput()
                ->with('success', $success)
                ->with("thePlayer", $playerArray);
        }elseif ($errors!=''){
            return Redirect::back()
                ->withInput()
                ->with('error', $errors)
                ->with("thePlayer", $playerArray);
        }else{
            return Redirect::back()
                ->withInput()
                ->with("thePlayer", $playerArray);
        }
    }

//    /**
//     * Remove the specified player from the team and move it to the single player list.
//     *
//     * @param  Player  $player
//     * @return \Illuminate\Http\Response
//     */
//    public function assignPlayerToTeam($player, $relation) //todo
//    {
//
//        if(Team::find($team_id)->isTeamNotFull()){
//            $playerToChange = PlayerRelation::having('player_relations.player_id', '=', $player['player_id'])
//                ->having('player_relations.relation_id', '=', $player['team_id'])
//                ->having('player_relations.relation_type', '=', ;
//            $playerToChange->relation_id = $team_id;
//            $playerToChange->save();
//        }else{
//            return Redirect::back()->withErrors(array('msg'=>'The team has the maximum amount of players. Please pick a different team.'));
//        }
//
//    }
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  Player  $player, Team $team
//     * @return \Illuminate\Http\Response
//     */
//    public function remove(Player $player, Team $team)
//    {
//        PlayerRelation::where('player_id', '=', $player->getRouteKey())
//            ->where('relation_id', '=', $team->getRouteKey())
//            ->where('relation_type', '=', Team::class)
//            ->delete();
//        return Redirect::back();
//    }
    /**
     * Destroy the specified resource from storage.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $name = $player->username;
        if($player->name!=''){
            $name.=" ( " . $player->name . ' )';
        }
        PlayerRelation::where('player_id', '=', $player->getRouteKey())->delete();
        $player->where('id', $player->getRouteKey())->delete();
        return Redirect::back()->with('success', "The player ". $name ." has been remove from all games, tournaments and teams.");
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
        $playerList = $players->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames($filterArray);

        return View::make('game/player')->with("players_filter", $playerList)->with('sorts',$ids);

    }

    /**
     * @param PlayerRequest $cleanedRequest
     * @return array
     */
    private function UserCleanUp(PlayerRequest $request)
    {
        $cleanedRequest = $request->all();
        $theAssociationRequest =[];

        for ($i=0; $i < count(Player::routables()); $i++) {
            // routable should be lowercase to match the incoming input
            $routable = strtolower(Player::routables()[$i]);

            // if this routable exist and is an array
            // then loop through and catch values
            // that are not defaults.
            if (isset($cleanedRequest[$routable . '_id']) && is_array($cleanedRequest[$routable . '_id'])) {

                foreach ($cleanedRequest[$routable . '_id'] as $k => $v) {
                    if (is_numeric($v)) {
                        $theAssociationRequest[$routable] = $v;
                    }
                }

            } elseif (isset($cleanedRequest[$routable . '_id']) && is_string($cleanedRequest[$routable . '_id'])) {
                $theAssociationRequest[$routable] = $cleanedRequest[$routable . '_id'];
            }
        }

        // unset values that should not be passes as mass assignable
        unset($cleanedRequest['_token']);
        unset($cleanedRequest['_method']);
        unset($cleanedRequest['submit']);

        // setup updated_by mass assignable values
        $cleanedRequest['updated_by'] = $this->getUserId();

        // replace request with new request list
        $request->replace($cleanedRequest);

        return array($request, $theAssociationRequest);
    }

    /**
     * @param PlayerRequest $request
     * @param Player $player
     * @param $theAssociation
     * @return array
     */
    private function getPlayerInfoAndErrors( PlayerRequest $request, Player $player, $theAssociation)
    {
        $player->name = $request->get('name');
        $player->username = $request->get('username');
        $player->email = $request->get('email');
        $player->phone = $request->get('phone');
        $player->updated_by =  $this->getUserId();
        $player->updated_on = Carbon::now("CST");
        $player->save();
        $player->fresh();

        $theAssociation['player'] = $player->id;
        $result = DB::transaction( function () use ($theAssociation) {
            PlayerRelation::where('player_id', '=', $theAssociation['player'])->delete();
            $result = '';
            if (count($theAssociation) > 1) {

                $result = Player::createRelation($theAssociation);
            }
            return $result;
        });
        $playerArray = $player->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames();
//        dd($playerArray);
        $success = '';
        $errors = '';
        if (isset($result) and $result != []) {
            if (isset($result['success'])) {
                $success .= "The player " . $playerArray['name'] . " was successfully attached to ".$result['success'];
            }

            if (isset($result['fail'])) {
                $errors .= "The player " . $playerArray['name'] . " couldn't be attached to ".$result['fail'];
            }
        }
        return array($playerArray, $success, $errors);
    }
}
