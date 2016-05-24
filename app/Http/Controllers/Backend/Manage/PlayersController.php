<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Player;
use App\Models\WpUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\PlayerRequest;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/player');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function create(PlayerRequest $request)
    {
        $player = new Player();
        $player->username = $request['username'];
        $player->email = $request['email'];
        $player->name = $request['name'];
        $player->phone = $request['phone'];
        $player->team_id = $request['team_id'];
        $player->updated_by =  $this->getUserId();
        $player->updated_on = Carbon::now("CST");
        $player->save();
//        dd($toUpdate);
//        dd("passed request");
//        $request->save('id', $request->getRouteKey())->update(
////        Player::where('id', $player->getRouteKey())->update(
//            $toUpdate
//        );
//        return View::make('player/player')->with("players", $this->retrievePlayers())->with("thePlayer", $player->where('id', $player->getRouteKey())->first())->with("cont_updated", true);
//        $player->save();
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Player  $player
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Player $player)
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
//        Player::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return View::make('game/player')->with("thePlayer", $player);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        return View::make('game/player')->with("thePlayer", $player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerRequest $request, Player $player) //have to update it to my request
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
        $player->where('id', $player->getRouteKey())->update(
//        Player::where('id', $player->getRouteKey())->update(
            $toUpdate
        );
        return View::make('game/player')->with("thePlayer", $player->where('id', $player->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        $player->where('id', $player->getRouteKey())->delete();
//        return View::make('player/player')->with("players", $this->retrievePlayers());
        return redirect('/manage/player');
    }
}
