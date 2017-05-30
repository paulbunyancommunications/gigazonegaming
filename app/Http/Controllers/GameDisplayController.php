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

    #lol arrays
    protected $summonerArray = array();
    protected $iconArray = array();
    protected $soloRankArray = array();
    protected $soloWinLossArray = array();
    protected $flexRankArray = array();
    protected $flexWinLossArray = array();

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
//        $this->serializeTeam($this->team);
        $this->setLolArrays();

        if($color == "Red"){
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        }
        else{
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }
        $team = Team::where('name','=',$team)->first();

        return view('/LeagueOfLegends/DisplayTeam', [
            'tournamet' => $tournament,    #NEW
            'teamName' => $team->name,
            'color' => $color,
            'summonerArray' => $this->summonerArray,
            'iconArray' => $this->iconArray,
            'soloRankArray' => $this->soloRankArray,
            'soloWinLossArray' => $this->soloWinLossArray,
            'flexRankArray' => $this->flexRankArray,
            'flexWinLossArray' => $this->flexWinLossArray
        ]);
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

    public function setLolArrays(){
        foreach($this->team as $player){
            array_push($this->summonerArray, $player->getSummonerName());
            array_push($this->iconArray, $player->getIcon());
            array_push($this->soloRankArray, $player->getSoloRank());
            array_push($this->soloWinLossArray, $player->getSoloRankedWinLoss());
            array_push($this->flexRankArray, $player->getFLEXRank());
            array_push($this->flexWinLossArray, $player->getFLEXRankedWinLoss());
        }
    }

    ####NEW
    #atore array of objects in Player object storage, so that .js can load objects and call getChampion.
    public function serializeTeam($team, $tournament){
        #store player object array in file for javascript to latter read from
        $s = serialize($this->team);
        file_put_contents("PlayerObjectStorage/" . $tournament. $team . 'PlayerObject.bin', $s);
    }

    public function championRequest(Request $request){

        $team = $request->team;
        return $team;

    }

}
