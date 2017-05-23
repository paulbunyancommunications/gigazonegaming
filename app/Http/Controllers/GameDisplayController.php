<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use GameDisplay\RiotDisplay\Summoner;

class GameDisplayController extends Controller
{
    protected $tournaments = array();
    protected $teams = array();
    public $team = array();

    #for testing purposes
    public function __construct()
    {

    }


    public function startGameDisplay()
    {
        $tournaments = Tournament::select('id', 'name')->get()->toArray();
        $teams = Team::select('id', 'name', 'tournament_id')->get()->toArray();
        $tournaments = json_encode($tournaments);
        $teams = json_encode($teams);

        return view('/LeagueOfLegends/startPage')->withTournaments($tournaments)->withTeams($teams);
    }

    public function teamViewDisplay($tournament, $team)
    {
//        dd($tournament, $team);
////        $this->setTeam($team, $tournament);
////        return view();
    }

    public function setTeams()
    {

    }
    public function setTournaments()
    {

    }
    public function setTeam($TeamName, $TournamentName)
    {

        #Select the team that has been chosen from the start page
        $team = Team::where('name', $TeamName)->first();
        $players = $team->players;

        #Loop through player of the chosen team and create an array of player objects
        foreach($players as $player){

            #Creat player object depending on which game is selected.
            switch ($TournamentName){
                #LOL
                case str_contains($TournamentName, "league-of-legends"):
                    $summoner = new Summoner($player->username);
                    array_push($this->team, $summoner);
                    break;
                #Overwatch

                #Default
                default:
                    break;
            }
        }
    }

}
