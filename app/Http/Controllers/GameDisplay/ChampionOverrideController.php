<?php

namespace App\Http\Controllers\GameDisplay;

use GameDisplay\RiotDisplay\API\Api;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class ChampionOverrideController extends Controller
{
    public function pageLoad()
    {
//        $allChampions = [];
//        $Url = 'https://na1.api.riotgames.com/lol/static-data/v3/champions?locale=en_US&dataById=false&api_key='.$_ENV['RIOT_API_KEY1'];
//        if(Cache::has('AllChampions')){
//            $champions = Cache::get('AllChampions');
//        }else{
//            $info = new Api();
//            if($champions = $info->apiRequest($Url)){
//                Cache::put('AllChampions',$champions,1440);
//                $champions = Cache::get('AllChampions');
//                foreach($champions->data as $champion){
//                    array_push($allChampions,$champion->key);
//                }
//                sort($allChampions);
//                return view('/LeagueOfLegends/championOverrideLayout')->with('allChampions',$allChampions);
        $allChampions=[];
        if(file_exists('../../tests/_data/Champions.bin')){
            $data = unserialize(file_get_contents('../../tests/_data/Champions.bin'));
            foreach($data->data as $champion){
                array_push($allChampions,$champion->key);
            }
            sort($allChampions);
        }
        return view('/LeagueOfLegends/championOverrideLayout')->with('allChampions',$allChampions);}
}
