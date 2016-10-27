<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Game;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Illuminate\Http\Request;

use App\Models\WpUser;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\TournamentRequest;

class TournamentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/tournament');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function store(TournamentRequest $request)
    {
        $tournament = new Tournament();
        $tournament->game_id = $request['game_id'];
        $tournament->max_players = $request['max_players'];
        $tournament->name = $request['name'];
        $tournament->updated_by =  $this->getUserId();
        $tournament->updated_on = Carbon::now("CST");
        $tournament->created_at = Carbon::now("CST");
        $tournament->updated_at = Carbon::now("CST");
        $tournament->save();
        return redirect('manage/tournament')->with('success',"The tournament ".$request['name']." was added");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Tournament  $tournament
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Tournament $tournament)
    {
        dd("Are you trying to hack us? ip_address:".$_SERVER['REMOTE_ADDR']);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        return View::make('game/tournament')->with("theTournament", $tournament);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament)
    {
        return View::make('game/tournament')->with("theTournament", $tournament);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(TournamentRequest $request, Tournament $tournament) //have to update it to my request
    {
        $updatedBy = $this->getUserId();
        $updatedOn = Carbon::now("CST");
        $toUpdate = array_merge($request->all(), [
            'updated_by' => $updatedBy,
            'updated_on' => $updatedOn
        ] );
        unset($toUpdate['_token']);
        unset($toUpdate['_method']);
        unset($toUpdate['id']);
        unset($toUpdate['reset']);
        unset($toUpdate['submit']);
        $tournament->where('id', $tournament->getRouteKey())->update(
            $toUpdate
        );
        return Redirect::back()->with('success',"The tournament ".$tournament->fresh()->name." was updated")
            ->with("theTournament", $tournament);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        PlayerRelation::where('relation_id', '=', $tournament->getRouteKey())->where('relation_type', '=', Tournament::class)->delete();
        foreach (Team::where('tournament_id', '=', $tournament->getRouteKey())->get() as $k => $t){
            PlayerRelation::where('relation_id', '=', $t->id)->where('relation_type', '=', Team::class)->delete();
            $t->delete();
        }
        $tournament->where('id', $tournament->getRouteKey())->delete();
        return Redirect::back();
    }
    /**
     * Display the specified resource.
     *
     * @param  Request $ids
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $ids)
    {
        if(trim($ids->game_sort) != "" and trim($ids->game_sort) != "---" and $ids->game_sort!=[]) {
            if(is_numeric($ids->game_sort)){
                $game = trim($ids->game_sort);
            }else {
                $game = "%" . trim($ids->game_sort) . "%";
            }
            $tournament =  Tournament::
            join('games', 'games.id', '=', 'tournaments.game_id')
                ->where('tournaments.game_id', '=', $game)
                ->select(['tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.max_players', 'tournaments.id as tournament_id','games.name as game_name'])
                ->orderBy('game_name', 'asc')
                ->orderBy('tournament_name', 'asc')
                ->get()
                ->toArray();
        }else{
            $tournament =  Tournament::join('games', 'games.id', '=', 'tournaments.game_id')
                ->select(['tournaments.name as tournament_name', 'tournaments.game_id', 'tournaments.max_players', 'tournaments.id as tournament_id','games.name as game_name'])
                ->get()
                ->toArray();
        }
        return View::make('game/tournament')->with("tournaments_filter", $tournament)->with("sorts", $ids);
    }

}
