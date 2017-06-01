<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use GameDisplay\RiotDisplay\Summoner;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Stmt\Return_;

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
    protected $championArray = array();

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
        $this->buildTheTeams($tournament, $team);
        $color = $this->setTeamColor($color);

        return view('/LeagueOfLegends/DisplayTeam', [
            'tournament' => $tournament,    #NEW
            'teamName' => $team,
            'color' => $color,
            'teamColor' => $color,
            'summonerArray' => $this->summonerArray,
            'iconArray' => $this->iconArray,
            'soloRankArray' => $this->soloRankArray,
            'soloWinLossArray' => $this->soloWinLossArray,
            'flexRankArray' => $this->flexRankArray,
            'flexWinLossArray' => $this->flexWinLossArray
        ]);
    }

    /**
     * @param $tournament
     * @param $team
     * @param $color
     * @return array
     */
    public function buildTheTeams($tournament, $team)
    {
        $this->setTeam($team, $tournament);
        //$this->serializeTeam($team);
        $this->setLolArrays();
    }

    public function setTeamColor($color){
        if ($color == "Red") {
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        } else {
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }

        return $color;
    }

    public function ajaxCheckRequest(Request $req)
    {
        $tournament = $req->tournament;
        $team =  $req->team;


        $this->setTeam($team,$tournament);
        $status = $this->team[0]->checkCurrentGameStatus();

        foreach ($this->team as $player) {
            $status = $player->checkCurrentGameStatus();
            if ($status) {
                    $player->setChampion();
                    array_push($this->championArray, $player->getChampion());


            }

        }
        return response()->json($this->championArray);
//        $this->fetchChampions();
//        return response()->json($this->championArray);



//        foreach ($this->team as $k => $player){
//        dd("k = ", $k, "player = ", $player);
//    }
//        $x = json_decode(json_encode($this->team));
//        return response()->json($x);

    }
    public function setTeam($TeamName, $TournamentName)
    {

        #Select the team that has been chosen from the start page
        $team = Team::where('name', $TeamName)->first();
        $players = $team->players;

        #Loop through player of the chosen team and create an array of player objects

        foreach($players as $player){
            if(isset($player) and isset($player->username) and $player->username != null) {
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
    public function serializeTeam($team){
        #store player object array in file for javascript to latter read from
        $teams = (array) $this->getTeam();

        $s = serialize($teams);
        file_put_contents(dirname(dirname(dirname(__DIR__)))."/storage/app/PlayerObjectStorage/" . str_replace(' ','',$team) . 'PlayerObject.bin', $s);
    }

    public function championRequest(Request $req){
        $team = $req->team;
        $array = array($team);



        return response()->json($array);
    }

    /**
     * @return array
     */
    public function getTeam()
    {
        return $this->team;
    }


    public function fetchChampions(){
        foreach($this->team as $player){
            $player->setChampion();
            array_push($this->championArray, $player->getChampion());
        }
    }

}
