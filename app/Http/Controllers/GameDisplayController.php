<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
    protected $summonerName = array();



    public function startGameDisplay()
    {
        $tournaments = Tournament::select('id', 'name')->get()->toArray();
        $teams = Team::select('id', 'name', 'tournament_id')->get()->toArray();
        $check = true;
        if(count($teams) > 1 ){
            while ($check) {
                $count = 0;
                for ($i = 0; $i < count($teams) - 1; $i++) {
                    if($teams[$i]["name"] > $teams[$i + 1]["name"]) {
                        $temp = $teams[$i];
                        $teams[$i] = $teams[$i + 1];
                        $teams[$i + 1] = $temp;
                    }
                    else{
                        $count++;
                    }
                    if ($count == count($teams)-1) {
                        $check = false;
                    }
                }
            }
        }
        $tournaments = json_encode($tournaments);
        $teams = json_encode($teams);
        return view('/LeagueOfLegends/startPage')->withTournaments($tournaments)->withTeams($teams);
    }


    public function customerDisplay()
    {
        return view('/LeagueOfLegends/customerPage');
    }

    public function championOverride()
    {
        return view('/LeagueOfLegends/championOverride');
    }

    public function team1ViewDisplay()
    {
        #Cache Set
        if (Cache::has('Team1Name') && Cache::has('Team1Info') && Cache::has('Team1Color')) {
            $teamName = Cache::get('Team1Name');
            $teamInfo = Cache::get('Team1Info');
            $teamColor = Cache::get('Team1Color');

            Cache::put('Team1CacheLoadedTimeStamp', Carbon::now(), 70);
            return view('/LeagueOfLegends/DisplayTeam', [
                'teamName' => $teamName,
                'color' => $teamColor,
                'teamColor' => $teamColor,
                'summonerArray' => $teamInfo['summonerArray'],
                'iconArray' => $teamInfo['iconArray'],
                'soloRankArray' => $teamInfo['soloRankArray'],
                'soloWinLossArray' => $teamInfo['soloWinLossArray'],
                'flexRankArray' => $teamInfo['flexRankArray'],
                'flexWinLossArray' => $teamInfo['flexWinLossArray']
            ]);
        } else {
            #Data Default data
            return view('/LeagueOfLegends/DisplayAltTeam');
        }
    }

    public function team2ViewDisplay()
    {
#Cache Set
        if (Cache::has('Team2Name') && Cache::has('Team2Info') && Cache::has('Team2Color')) {
            $teamName = Cache::get('Team2Name');
            $teamInfo = Cache::get('Team2Info');
            $teamColor = Cache::get('Team2Color');

            Cache::put('Team2CacheLoadedTimeStamp', Carbon::now(), 70);
            return view('/LeagueOfLegends/DisplayTeam', [
                'teamName' => $teamName,
                'color' => $teamColor,
                'teamColor' => $teamColor,
                'summonerArray' => $teamInfo['summonerArray'],
                'iconArray' => $teamInfo['iconArray'],
                'soloRankArray' => $teamInfo['soloRankArray'],
                'soloWinLossArray' => $teamInfo['soloWinLossArray'],
                'flexRankArray' => $teamInfo['flexRankArray'],
                'flexWinLossArray' => $teamInfo['flexWinLossArray']
            ]);
        } else {
            #Data Default data
            return view('/LeagueOfLegends/DisplayAltTeam');
        }
    }

    protected function returnView($TeamName, $TeamInfo, $TeamColor)
    {
        return view('/LeagueOfLegends/DisplayTeam', [
            'teamName' => $TeamName,
            'color' => $TeamColor,
            'teamColor' => $TeamColor,
            'summonerArray' => $TeamInfo['summonerArray'],
            'iconArray' => $TeamInfo['iconArray'],
            'soloRankArray' => $TeamInfo['soloRankArray'],
            'soloWinLossArray' => $TeamInfo['soloWinLossArray'],
            'flexRankArray' => $TeamInfo['flexRankArray'],
            'flexWinLossArray' => $TeamInfo['flexWinLossArray']
        ]);

    }

    public function getData(Request $req)
    {
        $team = $req->team;
        switch ($team) {
            case 'team1':
                if (Cache::has('Team1Name') && Cache::has('Team1Info') && Cache::has('Team1Color')) {
                    return 'true';
                }
            case 'team2':
                if (Cache::has('Team2Name') && Cache::has('Team2Info') && Cache::has('Team2Color')) {
                    return 'true';
                }
            default:
                return 'false';
        }
    }

    public function updateData(Request $req)
    {
        $team = $req->team;
        $checkChamp = $req->checkChamp;

        $returnArray = array();
        $returnArray[0] = 'false';
        $returnArray[1] = 'false';
        $returnArray[2] = 'false';
        switch ($team) {
            case 'team1':
                if (Cache::has('Team1CacheLoadedTimeStamp') and Cache::has('Team1TimeStamp')) {
                    if (Cache::get('Team1CacheLoadedTimeStamp') < Cache::get('Team1TimeStamp')) {
                        $returnArray[0] = 'true';
                    }
                } else {
                    $returnArray[0] = 'true';
                    return $returnArray;
                }
                if ($checkChamp) {
                    if (Cache::has('Team1Champions')) {
                        $returnArray[1] = Cache::get('Team1Champions');
                        $returnArray[2] = Cache::get('Team1ChampionsPlayerId');
                        Cache::put('Team1ChampionsCheck', false, 70);
                    }
                }
                break;
            case 'team2':
                if (Cache::has('Team2CacheLoadedTimeStamp') and Cache::has('Team2TimeStamp')) {
                    if (Cache::get('Team2CacheLoadedTimeStamp') < Cache::get('Team2TimeStamp')) {
                        $returnArray[0] = 'true';
                    }
                } else {
                    $returnArray[0] = 'true';
                    return $returnArray;
                }
                if ($checkChamp) {
                    if (Cache::has('Team2Champions')) {
                        $returnArray[1] = Cache::get('Team2Champions');
                        $returnArray[2] = Cache::get('Team2ChampionsPlayerId');
                        Cache::put('Team2ChampionsCheck', false, 70);
                    }
                }
                break;
        }

        return $returnArray;
    }

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

    public function clearCache()
    {
        Cache::flush();
        return "Cache Successfully Cleared";
    }

    public function getTeamName()
    {
        $teamNames = array();
        if (Cache::has('Team1Name') && Cache::has('Team2Name') && Cache::has('Team1Color') && Cache::has('Team2Color')) {
            array_push($teamNames, Cache::get('Team1Name'));
            array_push($teamNames, Cache::get('Team2Name'));
            array_push($teamNames, Cache::get('Team1Color'));
            array_push($teamNames, Cache::get('Team2Color'));
            return $teamNames;
        }
    }













    public function teamViewDisplay($tournament, $team, $color)
    {
        $this->buildTheTeams($tournament, $team);
        if ($this->summonerArray[0] == "") {
            $color = $this->setTeamColor($color);
            return view('/LeagueOfLegends/DisplayAltTeam', [
                'tournament' => $tournament,    #NEW
                'teamName' => $team,
                'color' => $color,
            ]);

        } else {
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

    public function setTeamColor($color)
    {
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
        $team = $req->team;


        $this->setTeam($team, $tournament);
        $status = $this->team[0]->checkCurrentGameStatus();

        $i = 0;
        foreach ($this->team as $player) {
            $status = $player->checkCurrentGameStatus();
            if ($status) {
                $player->setChampion();
                array_push($this->championArray, $player->getChampion());
                array_push($this->summonerArray, $i);
            }
            $i++;
        }
        $returnArray = array(
            'Champions' => $this->championArray,
            'Summoners' => $this->summonerArray
        );
        return response()->json($returnArray);
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
        $i = 0;
        foreach ($players as $player) {
            if (isset($player) and isset($player->username) and $player->username != null) {
                #Creat player object depending on which game is selected.
                switch ($TournamentName) {
                    #LOL
                    case str_contains($TournamentName, "league-of-legends"):
                        $summoner = new Summoner($player->username, $i);
                        $i++;
                        if ($i > 10) {
                            $i = 0;
                        }
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

    public function setLolArrays()
    {
        foreach ($this->team as $player) {
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
    public function serializeTeam($team)
    {
        #store player object array in file for javascript to latter read from
        $teams = (array)$this->getTeam();

        $s = serialize($teams);
        file_put_contents(dirname(dirname(dirname(__DIR__))) . "/storage/app/PlayerObjectStorage/" . str_replace(' ', '', $team) . 'PlayerObject.bin', $s);
    }

    public function championRequest(Request $req)
    {
        $team = $req->team;
        $array = array($team);


        return response()->json($array);
    }
}