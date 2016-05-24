<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Game;
use App\Models\Championship\Team;
use Illuminate\Http\Request;

use App\Models\WpUser;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\TeamRequest;
class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/team');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function create(TeamRequest $request)
    {
        $team = new Team();
//        dd($request->all());
        $team->tournament_id = $request['tournament_id'];
        $team->name = $request['name'];
        $team->emblem = $request['emblem'];
        $team->updated_by =  $this->getUserId();
        $team->updated_on = Carbon::now("CST");
        $team->save();
//        dd($toUpdate);
//        dd("passed request");
//        $request->save('id', $request->getRouteKey())->update(
////        Team::where('id', $team->getRouteKey())->update(
//            $toUpdate
//        );
//        return View::make('team/team')->with("theTeam", $team->where('id', $team->getRouteKey())->first())->with("cont_updated", true);
//        $team->save();
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Team  $team
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Team $team)
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
//        Team::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        return View::make('game/team')->with("theTeam", $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        return View::make('game/team')->with("theTeam", $team);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(TeamRequest $request, Team $team) //have to update it to my request
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
        $team->where('id', $team->getRouteKey())->update(
//        Team::where('id', $team->getRouteKey())->update(
            $toUpdate
        );
        return View::make('game/team')->with("theTeam", $team->where('id', $team->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $team->where('id', $team->getRouteKey())->delete();
//        return View::make('team/team');
        return redirect('/manage/team');
    }
}
