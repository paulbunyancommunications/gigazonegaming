<?php

namespace App\Http\Controllers\GameDisplay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;

class AdminPageController extends Controller
{
    /**
     *
     * @return view
     */
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
        return view('/LeagueOfLegends/admin')->withTournaments($tournaments)->withTeams($teams);
    }
}
