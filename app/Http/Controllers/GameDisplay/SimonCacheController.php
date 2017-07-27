<?php

namespace App\Http\Controllers\GameDisplay;

use GameDisplay\RiotDisplay\API\Api;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use Carbon\Carbon;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use GameDisplay\RiotDisplay\Summoner;
use Mockery\Exception;
use function MongoDB\BSON\toJSON;
use PhpParser\Node\Stmt\Return_;
use App\Http\Controllers\Controller;

class SimonCacheController extends Controller
{

    protected $players = array();


    protected $summonerArray = array();
    protected $iconArray = array();
    protected $soloRankArray = array();
    protected $soloWinLossArray = array();
    protected $flexRankArray = array();
    protected $flexWinLossArray = array();
    protected $apiIterator = 0;

    public function SubmitCache(Requests\SimonCacheSubmitCache $req)
    {
        ///For Assertion Test: Load fixture if the exists.
        $testing = \utilphp\util::str_to_bool($req->header('Testing'));
        if($testing and file_exists('../../tests/_data/PlayerInfoArray.bin')){
            $data = unserialize(file_get_contents('../../tests/_data/PlayerInfoArray.bin'));
            $this->cacheContent($data['teamInfo'],$data['colors'],$data['teamName'],$data['players']);
            return $data;
        }


        $tournament = $req->tournament;
        $team = $req->team;
        $color = $req->color;
        $teamInfoArrays = array();
        $colorArray = array();
        $playersArray = array();

        try{
            for($i = 0; $i < 2; $i++){
                $this->buildTheTeams($tournament, $team[$i]);
                $colorResult = $this->setTeamColor($color[$i]);
                array_push($teamInfoArrays,$this->makeTeam());
                array_push($colorArray,$colorResult);
                array_push($playersArray, $this->players);
                $this->resetArrays();
            }

            $this->cacheContent($teamInfoArrays,$colorArray,$team,$playersArray);
            $returnArray = array(
                'teamName' => $team,
                'teamInfo' => $teamInfoArrays,
                'colors' => $colorArray,
                'ErrorCode' => false,
                'players' => $playersArray
            );
        }catch(Exception $e){
            $returnArray = array(
                'ErrorCode' => true,
                'ErrorMessage' => $e->getMessage(),
            );
        }
        if($testing){
            $data = serialize($returnArray);
            file_put_contents('../../tests/_data/PlayerInfoArray.bin', $data);
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

    /**
     * @param $TeamName
     * @param $TournamentName
     */
    public function setPlayers($TeamName, $TournamentName)
    {

        #Select the team that has been chosen from the start page
        $team = Team::where('name', $TeamName)->firstOrFail();
        $players = $team->players;

        #Loop through player of the chosen team and create an array of player objects
        foreach($players as $player){
            if(isset($player) and isset($player->username) and $player->username != null) {
                #Creat player object depending on which game is selected.
                switch ($TournamentName){
                    #LOL
                    case str_contains($TournamentName, "league-of-legends"):
                        $api = new Api($this->apiIterator);
                        $summoner = new Summoner($player->username, $api);
                        array_push($this->players, $summoner);
                        $this->apiIterator++;

                        //Reset Api Key Counter
                        if($this->apiIterator == 10){
                            $this->apiIterator = 0;
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

    /**
     *
     */
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

    /**
     * @param $color1
     * @return string
     */
    public function setTeamColor($color1){
        if ($color1 == "Red") {
            return "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        }
        else {
            return "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }
    }

    /**
     * @return array
     */
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

    /**
     *
     */
    public function resetArrays(){
            foreach ($this as $key => $value) {
                if($this->$key != $this->apiIterator) {
                    $this->$key = array();
                }
            }
    }

    /**
     * @param $teamInfoArrays
     * @param $colorArray
     * @param $team
     * @param $players
     */
    public function cacheContent($teamInfoArrays, $colorArray, $team, $players){
        Cache::put('Players', $players, 70);
        Cache::put('Team1Name', $team[0], 70);
        Cache::put('Team1Info', $teamInfoArrays[0], 70);
        Cache::put('Team1Color', $colorArray[0], 70);
        Cache::put('Team1TimeStamp', Carbon::now(), 70);
        Cache::put('Team2Name', $team[1], 70);
        Cache::put('Team2Info', $teamInfoArrays[1], 70);
        Cache::put('Team2Color', $colorArray[1], 70);
        Cache::put('Team2TimeStamp', Carbon::now(), 70);
    }

    public function cacheIconRefreshBool(Request $req){
        Cache::put($req->team . 'IconRefresh', true, 70);
        return "$req->team IconRefresh Ready For Refresh";
    }


    /**
     * @return string
     */
    public function clearCache()
    {
        Cache::flush();
        return "Cache Successfully Cleared";
    }

    /**
     * @param Request $req
     * @return string
     */
    public function cacheChampionOverride(Request $req)
    {
        $championArray = $req->championArray;

        $team = $req->team;
        $championPlayerIdArray = [0, 1, 2, 3, 4];
        if ($team == 'Team 1') {
            Cache::put('Team1Champions', $championArray, 70);
            Cache::put('Team1ChampionsPlayerId', $championPlayerIdArray, 70);
        } else {
            Cache::put('Team2Champions', $championArray, 70);
            Cache::put('Team2ChampionsPlayerId', $championPlayerIdArray, 70);
        }
        return $team." Champions Successfully Updated!!";
    }

    /**
     * @return array
     */
    public function getChampions(){

        $apiKeyArray=array();

        if(Cache::has('Players')){
            try{
                $players = Cache::get('Players');
                $championArray = [[],[]];
                $championPlayerId = [[],[]];
                #t for team
                for($t = 0; $t < count($players); $t++){
                    for($p = 0; $p < count($players[$t]); $p++){
                        $status = $players[$t][$p]->checkCurrentGameStatus();
                        if($status) {
                            $players[$t][$p]->setChampion();
                            array_push($championArray[$t], $players[$t][$p]->getChampion());
                            array_push($championPlayerId[$t], $p);
                        }
                    }
                }
                if($championArray != [[],[]]){
                    if($championArray[0] != []){
                        Cache::put('Team1Champions', $championArray[0], 70);
                        Cache::put('Team1ChampionsPlayerId', $championPlayerId[0], 70);
                    }if($championArray[1] != []){
                        Cache::put('Team2Champions', $championArray[1], 70);
                        Cache::put('Team2ChampionsPlayerId', $championPlayerId[1], 70);
                    }
                    $returnArray = array('Champions' => $championArray, 'ChampionsPlayersId'=>$championPlayerId, 'ErrorCode' => 'false');
                    return $returnArray;
                }
                return array('ErrorCode' => 'true', 'ErrorMessage' => 'Champions are not ready.');
            }catch(\Exception $e){
                return array('ErrorCode' => 'true', 'ErrorMessage' => $e->getMessage() , 'ApiArray' => $apiKeyArray);
            }

        }
        return array('ErrorCode' => 'true', 'ErrorMessage' => 'The cache is not available. Please Select a team and a color before getting champions.');

    }
}