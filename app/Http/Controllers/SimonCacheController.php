<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use GameDisplay\RiotDisplay\Summoner;
use Mockery\Exception;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Stmt\Return_;

class cacheController extends Controller
{

    protected $players = array();


    protected $summonerArray = array();
    protected $iconArray = array();
    protected $soloRankArray = array();
    protected $soloWinLossArray = array();
    protected $flexRankArray = array();
    protected $flexWinLossArray = array();

    public function teamViewDisplay(Request $req)
    {
        $tournament = $req->tournament;
        $team = $req->team;
        $color = $req->color;

        $teamInfoArrays = array();
        $colorArray = array();

        try{
            for($i = 0; $i < count($team); $i++){
                $this->buildTheTeams($tournament, $team[$i]);
                $color = $this->setTeamColor($color[$i]);
                array_push($teamInfoArrays,$this->makeTeam());
                array_push($colorArray,$color);
                $this->resetArrays();
            }

            $this->cacheContent($teamInfoArrays,$colorArray);
            $returnArray = array(
                'teamName' => $team,
                'teamInfo' => $teamInfoArrays,
                'colors' => $colorArray,
                'ErrorCode' => false
            );
        }catch(Exception $e){
            $returnArray = array(
                'ErrorCode' => true,
                'ErrorMessage' => $e->getMessage()
            );
        }

        return $returnArray;
    }
    /**
     * @param $tournament
     * @param $team
     * @param $color
     * @return array
     */
    public function buildTheTeams($tournament, $team)
    {
        //Makes an array of player objects
        $this->setPlayers($team, $tournament);
        //$this->serializeTeam($team);
        $this->setLolArrays();
    }

    public function setPlayers($TeamName, $TournamentName)
    {

        #Select the team that has been chosen from the start page
        $team = Team::where('name', $TeamName)->first();
        $players = $team->players;

        #Loop through player of the chosen team and create an array of player objects
        $i = 0;
        foreach($players as $player){
            if(isset($player) and isset($player->username) and $player->username != null) {
                #Creat player object depending on which game is selected.
                switch ($TournamentName){
                    #LOL
                    case str_contains($TournamentName, "league-of-legends"):
                        $summoner = new Summoner($player->username, $i);
                        array_push($this->players, $summoner);
                        $i++;

                        //Reset Api Key Counter
                        if($i == 10){
                            $i = 0;
                        }
                        break;
                    #Overwatch

                    #Default
                    default:
                        break;
                }
            }
        }
    }
    public function setLolArrays(){
        foreach($this->players as $player){
            array_push($this->summonerArray, $player->getSummonerName());
            array_push($this->iconArray, $player->getIcon());
            array_push($this->soloRankArray, $player->getSoloRank());
            array_push($this->soloWinLossArray, $player->getSoloRankedWinLoss());
            array_push($this->flexRankArray, $player->getFLEXRank());
            array_push($this->flexWinLossArray, $player->getFLEXRankedWinLoss());
        }
    }
    public function setTeamColor($color){
        if ($color == "Red") {
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        } else {
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }

        return $color;
    }
    public function makeTeam(){
        $team = array(
            'summonerArray' => $this->summonerArray,
            'iconArray' => $this->iconArray,
            'soloRankArray' => $this->soloRankArray,
            'soloWinLossArray' => $this->soloWinLossArray,
            'flexRankArray' => $this->flexRankArray,
            'flexWinLossArray' => $this->flexWinLossArray,
        );
        return $team;
    }
    public function resetArrays(){
        foreach ($this as $key => $value) {
            $this->$key = array();
        }
    }
    public function cacheContent($teamInfoArrays,$colorArray){
//        $cache = new Cache();
//        $cache->put('Team1Info', $teamInfoArrays[1], 70);
//        $cache->put('Team1Color', $colorArray[1], 70);
//        $cache->put('Team2Info', $teamInfoArrays[2], 70);
//        $cache->put('Team2Color', $colorArray[2], 70);
    }
}
