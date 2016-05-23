<?php

namespace App\Http\Controllers\Backend\Manage;

use Illuminate\Http\Request;

use App\Models\Championship\Tournament;
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
    private function retrieveTournaments(){
        return Tournament::all()->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/tournament')->with("tournaments", $this->retrieveTournaments());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function create(TournamentRequest $request)
    {
        $tournament = new Tournament();
        $tournament->uri = $request['uri'];
        $tournament->description = $request['description'];
        $tournament->name = $request['name'];
        $tournament->updated_by =  $this->getUserId();
        $tournament->updated_on = Carbon::now("CST");
        $tournament->save();
//        dd($toUpdate);
//        dd("passed request");
//        $request->save('id', $request->getRouteKey())->update(
////        Tournament::where('id', $tournament->getRouteKey())->update(
//            $toUpdate
//        );
//        return View::make('tournament/tournament')->with("tournaments", $this->retrieveTournaments())->with("theTournament", $tournament->where('id', $tournament->getRouteKey())->first())->with("cont_updated", true);
//        $tournament->save();
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Tournament  $tournament
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Tournament $tournament)
    {
        dd("Are you trying to hack us? ip_address:".$_SERVER['REMOTE_ADDR']);
//        $updatedBy = $this->getUserId();
//        $updatedOn = Carbon::now("CST");
//        $toUpdate = array_merge($request->all(), [
//            'updated_by' => $updatedBy,
//            'updated_on' => $updatedOn
//        ] );
//        unset($toUpdate['_token']);
//        unset($toUpdate['_method']);
//        unset($toUpdate['id']);
//        unset($toUpdate['reset']);
//        unset($toUpdate['submit']);
//        Tournament::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        return View::make('game/tournament')->with("tournaments", $this->retrieveTournaments())->with("theTournament", $tournament);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function edit(Tournament $tournament)
    {
        return View::make('game/tournament')->with("tournaments", $this->retrieveTournaments())->with("theTournament", $tournament);
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
//        dd($toUpdate);
//        dd("passed request");
        $tournament->where('id', $tournament->getRouteKey())->update(
//        Tournament::where('id', $tournament->getRouteKey())->update(
            $toUpdate
        );
        return View::make('game/tournament')->with("tournaments", $this->retrieveTournaments())->with("theTournament", $tournament->where('id', $tournament->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        $tournament->where('id', $tournament->getRouteKey())->delete();
//        return View::make('tournament/tournament')->with("tournaments", $this->retrieveTournaments());
        return redirect('/manage/tournament');
    }
}
