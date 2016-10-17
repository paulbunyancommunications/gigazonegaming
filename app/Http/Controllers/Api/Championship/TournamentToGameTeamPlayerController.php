<?php

namespace App\Http\Controllers\Api\Championship;

use App\Models\Championship\Game;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Http\Request;

use App\Http\Requests;

class TournamentToGameTeamPlayerController extends \App\Http\Controllers\Controller
{
    /**
     * Get all the games
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(Request $tournament)
    {
        $tournamentRet = $this->returnAll(Tournament::where('name', '=', $tournament->name)->first());
        return $tournamentRet;
    }

    /**
     * Try and get Game by Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tournament $tournament)
    {
        $tournamentRet = $this->returnAll($tournament);
        return $tournamentRet;
    }
    private function returnAll(Tournament $tournament){
        $t = $tournament;
        $game = $t->game()->first();
        $teams = $tournament->teams()->get();
        $teamArray = [];
        foreach ($teams as $team){
            $players = $team->getPlayersAttribute();
            $teamArray[$team->name] = [];
            $i = 1;
            foreach ($players as $player){
                if($player->id == $team->captain){
                    $teamArray[$team->name]['captain'] = $player->username;
                }else {
                    $teamArray[$team->name]['player_'.$i] = $player->username;
                    $i++;
                }
            }
        }
        $returnableArray = [
            'game'=>
                [
                    'name' => $game->name,
                    'title' => $game->title
                ],
            'tournament'=>
                [
                    'name' => $tournament->name,
                    'max_players' => $tournament->max_players
                ],
            'teams'=> $teamArray
        ];
        return json_encode($returnableArray,JSON_PRETTY_PRINT);
    }
}
