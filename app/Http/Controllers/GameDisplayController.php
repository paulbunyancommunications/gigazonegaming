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

    public function teamViewDisplay($tournament,$team,$color)
    {
        $this->setTeam($team,$tournament);

        if($color == "Red"){
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        }
        else{
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }
        $team = Team::where('name','=',$team)->first();

        return view('/LeagueOfLegends/DisplayTeam', [
            'teamName' => $team->name,
            'color' => $color,
            'team' => $this->team
        ]);
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
