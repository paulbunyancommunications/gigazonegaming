<?php

namespace App\Http\Controllers\GameDisplay;


use App\Http\Controllers\Controller;
use App\Models\Championship\Team;
use Illuminate\Http\Request;
use GameDisplay\RiotDisplay\Summoner;

use App\Http\Requests;

class GameDisplayController extends Controller
{


# Variables
#----------------------------------------------------------------------
    protected $tournaments = array();
    protected $teams = array();
    protected $team = array();

    public function __construct()
    {

    }

# Methods for views
#----------------------------------------------------------------------
    public function startGameDisplay()
    {

    }

    public function teamViewDisplay($TeamName, $TournamentName)
    {
        $this->setTeam($TeamName, $TournamentName);
        return view();
    }


# setters
#----------------------------------------------------------------------
    public function setTeams()
    {

    }
    public function setTournaments()
    {

    }
    public function setTeam($TeamName, $TournamentName)
    {

        #Select the team row
        $team = Team::where('name', $TeamName)->first();
        $players = $team->players;
        foreach($players as $player){
            switch ($TournamentName){
                #LOL
                case str_contains($TournamentName, "league-of-legends"):
                    $summoner = new Summoner($player->username);
                    dd("Summoner");
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
