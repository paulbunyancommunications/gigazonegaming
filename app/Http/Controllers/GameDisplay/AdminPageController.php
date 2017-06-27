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
        $tournaments = json_encode($tournaments);
        $teams = json_encode($teams);
        return view('/LeagueOfLegends/admin')->withTournaments($tournaments)->withTeams($teams);
    }
}
