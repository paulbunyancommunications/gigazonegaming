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
                'summonerArray' => $teamInfo['summonerArray'],
                'iconArray' => $teamInfo['iconArray'],
                'soloRankArray' => $teamInfo['soloRankArray'],
                'soloWinLossArray' => $teamInfo['soloWinLossArray'],
                'flexRankArray' => $teamInfo['flexRankArray'],
                'flexWinLossArray' => $teamInfo['flexWinLossArray'],
                'top3ChampionIcons' => $teamInfo['top3ChampionIcons'],
                'top3ChampionImages' => $teamInfo['top3ChampionImages'],
                'top3ChampionRanks' => $teamInfo['top3ChampionRanks'],
                'top3ChampionPoints' => $teamInfo['top3ChampionPoints']
            ]);
        }
        #Data Default data
        $fileContents = glob(public_path("content/LeagueImages/*.*"));
        $images=[];
        for($i=0; $i<count($fileContents);$i++){
            $brokenPath = explode('/',$fileContents[$i]);
            $neededPath = array_slice($brokenPath,5);
            $fixedPath= implode("/",$neededPath);
            array_push($images,$fixedPath);
        }
        $timestamp = filemtime(public_path("content/LeagueImages/"));
        Cache::put("ImageDirTimestamp",$timestamp,150000);
        return view('/LeagueOfLegends/DisplayAltTeam')->withImages($images);
    }
    public function CarouselUpdate(){
        if(Cache::has("ImageDirTimestamp")){
            $oldTimestamp = Cache::get("ImageDirTimestamp");
            $timestamp = filemtime(public_path("content/LeagueImages/"));
            if($timestamp > $oldTimestamp){
                $fileContents = glob(public_path("content/LeagueImages/*.*"));
                $images=[];
                for($i=0; $i<count($fileContents);$i++){
                    $brokenPath = explode('/',$fileContents[$i]);
                    $neededPath = array_slice($brokenPath,5);
                    $fixedPath= implode("/",$neededPath);
                    array_push($images,$fixedPath);
                }
                Cache::put("ImageDirTimestamp",$timestamp,150000);
                return response()->json($images);
            }
        }
        $timestamp = filemtime(public_path("content/LeagueImages/"));
        Cache::put("ImageDirTimestamp",$timestamp,150000);

        return response()->json(false);
    }

    public function getData(Requests\GameDisplayGetDataRequest $req)
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

    public function updateData(Requests\GameDisplayUpdateDataRequest $req)
    {
        $team = $req->team;
        $checkChamp = $req->checkChamp;


        $returnArray = array();
        $returnArray[0] = 'false'; #Bool For Page Reload (Meaning there has or hasn't been resubmitted data)
        $returnArray[1] = 'false'; #Champion Images will be loaded into this array or False if Images are not available.
        $returnArray[2] = 'false'; #Player Ids that are 1:1 with champion images with be stored here to keep track of who has which champion.
        $returnArray[3] = $checkChamp; #Bool that states if champion cache needs to be checked.
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
}