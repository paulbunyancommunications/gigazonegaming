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
    public function teamViewDisplay($team)
    {
        $teamNumber = explode('m',$team)[1];

        #Cache Set
        if (Cache::has('Team'.$teamNumber.'Name') && Cache::has('Team'.$teamNumber.'Info') && Cache::has('Team'.$teamNumber.'Color')) {
            $teamName = Cache::get('Team'.$teamNumber.'Name');
            $teamInfo = Cache::get('Team'.$teamNumber.'Info');
            $teamColor = Cache::get('Team'.$teamNumber.'Color');

            Cache::put('Team'.$teamNumber.'CacheLoadedTimeStamp', Carbon::now(), 70);
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
        }else{
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
}