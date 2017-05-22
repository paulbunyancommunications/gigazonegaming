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
        $teams = Team::all();
        foreach($teams as $team){
            array_push($this->teams,$team->name);
        }
        $teams = $this->teams;
        $teams = json_encode($teams);

        $tournaments = Tournament::all();
        foreach($tournaments as $tournament){
            array_push($this->tournaments,$tournament->name);
        }
        $tournaments = $this->tournaments;
        $tournaments = json_encode($tournaments);

        return view('/LeagueOfLegends/startPage',compact('teams','tournaments'));
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
