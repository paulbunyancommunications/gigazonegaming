<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class GameDisplayController extends Controller
{
    protected $tournaments = array();
    protected $teams = array();
    protected $team = array();

    public function startGameDisplay()
    {
        $tournaments = Tournament::select('id', 'name')->get()->toArray();
        $teams = Team::select('id', 'name', 'tournament_id')->get()->toArray();
        $tournaments = json_encode($tournaments);
        $teams = json_encode($teams);

        return view('/LeagueOfLegends/startPage')->withTournaments($tournaments)->withTeams($teams);
    }

    public function teamViewDisplay()
    {

    }

    public function setTeams()
    {

    }
    public function setTournaments()
    {

    }
    public function setTeam()
    {

    }

}
