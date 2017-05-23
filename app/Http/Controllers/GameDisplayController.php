<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

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

    public function teamViewDisplay($tournament,$team,$color)
    {
        if($color == "Red"){
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(255,0,0,0.2); width:100%; height:auto; min-height:100%";
        }
        else{
            $color = "background-size:cover; box-shadow:inset 0 0 0 2000px rgba(0,0,255,0.2); width:100%; height:auto; min-height:100%";
        }
        $team = Team::where('name','=',$team)->get();
        $team = json_encode($team);
        $color = json_encode($color);

        return view('/LeagueOfLegends/DisplayTeam')->withTeam($team)->withColor($color);
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
