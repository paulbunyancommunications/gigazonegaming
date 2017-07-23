<?php

namespace App\Http\Controllers\GameDisplay;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Http\Requests;
use GameDisplay\RiotDisplay\Summoner;
use App\Http\Controllers\Controller;

/**
 * Class GameDisplayController
 * @package App\Http\Controllers\GameDisplay
 */
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
        }
        #Data Default data
        return view('/LeagueOfLegends/DisplayAltTeam');
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
        }
        #Data Default data
        return view('/LeagueOfLegends/DisplayAltTeam');
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

    public function getData(Requests\GameDisplayGetData $req)
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
        $returnArray[3] = $checkChamp;
        $teamNumber = explode('m',$team)[1];

        if (Cache::has('Team'.$teamNumber.'CacheLoadedTimeStamp') and Cache::has('Team'.$teamNumber.'TimeStamp')) {
            if (Cache::get('Team'.$teamNumber.'CacheLoadedTimeStamp') < Cache::get('Team'.$teamNumber.'TimeStamp')) {
                $returnArray[0] = 'true';
            }
        } else {
            $returnArray[0] = 'true';
            return $returnArray;
        }
        if ($checkChamp === 'true') {
            if (Cache::has('Team'.$teamNumber.'Champions')) {
                $returnArray[1] = Cache::get('Team'.$teamNumber.'Champions');
                $returnArray[2] = Cache::get('Team'.$teamNumber.'ChampionsPlayerId');
            }
        }
        return $returnArray;
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

        }

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

        $serializeData = serialize($teams);
        file_put_contents(dirname(dirname(dirname(__DIR__))) . "/storage/app/PlayerObjectStorage/" . str_replace(' ', '', $team) . 'PlayerObject.bin', $serializeData);
    }

    public function championRequest(Request $req)
    {
        $team = $req->team;
        $array = array($team);


        return response()->json($array);
    }
}