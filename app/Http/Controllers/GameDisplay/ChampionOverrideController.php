<?php

namespace App\Http\Controllers\GameDisplay;

use GameDisplay\RiotDisplay\API\Api;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class ChampionOverrideController extends Controller
{
    public function pageLoad()
    {
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
