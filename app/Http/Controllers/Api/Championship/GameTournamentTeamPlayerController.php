<?php

namespace App\Http\Controllers\Api\Championship;

use App\Models\Championship\Game;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Http\Request;

use App\Http\Requests;

class GameTournamentTeamPlayerController extends \App\Http\Controllers\Controller
{
    /**
     * Get all the games
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function to_name(Request $tournament)
    {
        $t = Tournament::where('name', '=', $tournament->name)->first();
        if ($t == null) {
            return  json_encode('false');
        }
        $tournamentRet = $this->returnFromTournament($t);
        if (isset($tournamentRet['error'])) {
            return  json_encode('false');
        }
        return $tournamentRet;
    }

    /**
     * Try and get Game by Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function to_id(Request $tournament)
    {
        $t = Tournament::where('id', '=', $tournament->id)->first();
        if ($t == null) {
            return  json_encode('false');
        }
        $tournamentRet = $this->returnFromTournament($t);
        if (isset($tournamentRet['error'])) {
            return  json_encode('false');
        }
        return $tournamentRet;
    }
    /**
     * Get all the games
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function te_name(Request $team)
    {
        $t = Team::where('name', '=', $team->name)->first();
        if ($t == null) {
            return  json_encode('false');
        }
        $teamRet = $this->returnFromTeam($t);
        if (isset($tournamentRet['error'])) {
            return  json_encode('false');
        }
        return $teamRet;
    }

    /**
     * Try and get Game by Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function te_id(Request $team)
    {
        $t = Team::where('id', '=', $team->id)->first();
        if ($t == null) {
            return  json_encode('false');
        }
        $teamRet = $this->returnFromTeam($t);
        if (isset($tournamentRet['error'])) {
            return  json_encode('false');
        }
        return $teamRet;
    }





    private function returnFromTournament(Tournament $tournament)
    {
        $t = $tournament;
        $game = $t->game()->first();
        $teams = $tournament->teams()->get();
        $teamArray = [];
        foreach ($teams as $team) {
            $players = $team->getPlayersAttribute();
            $teamArray[$team->name] = [];
            $i = 1;
            foreach ($players as $player) {
                if ($player->id == $team->captain) {
                    $teamArray[$team->name]['captain'] = $player->username;
                } else {
                    $teamArray[$team->name]['player_'.$i] = $player->username;
                    $i++;
                }
            }
        }
        $returnableArray = [
            'games'=>
                [
                    'name' => $game->name,
                    'title' => $game->title
                ],
            'tournaments'=>
                [
                    'name' => $tournament->name,
                    'max_players' => $tournament->max_players
                ],
            'teams'=> $teamArray
        ];
        return json_encode($returnableArray, JSON_PRETTY_PRINT);
    }
    private function returnFromTeam(Team $team)
    {
        $tournament = $team->tournament()->first();
        $game = $tournament->game()->first();
        $players = $team->getPlayersAttribute();
        $teamArray = [];
        $i = 1;
        foreach ($players as $player) {
            if ($player->id == $team->captain) {
                $teamArray[$team->name]['captain'] = $player->username;
            } else {
                $teamArray[$team->name]['player_'.$i] = $player->username;
                $i++;
            }
        }
        $returnableArray = [
            'games'=>
                [
                    'name' => $game->name,
                    'title' => $game->title
                ],
            'tournaments'=>
                [
                    'name' => $tournament->name,
                    'max_players' => $tournament->max_players
                ],
            'teams'=> $teamArray
        ];
        return json_encode($returnableArray, JSON_PRETTY_PRINT);
    }
}
