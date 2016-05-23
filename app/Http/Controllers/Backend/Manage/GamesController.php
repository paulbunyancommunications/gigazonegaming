<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Models\Championship\Game;
use App\Models\WpUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Requests\GameRequest;

class GamesController extends Controller
{
    private function retrieveGames(){
        return Game::all()->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/game')->with("games", $this->retrieveGames());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function create(GameRequest $request)
    {
        $game = new Game();
        $game->uri = $request['uri'];
        $game->description = $request['description'];
        $game->name = $request['name'];
        $game->updated_by =  $this->getUserId();
        $game->updated_on = Carbon::now("CST");
        $game->save();
//        dd($toUpdate);
//        dd("passed request");
//        $request->save('id', $request->getRouteKey())->update(
////        Game::where('id', $game->getRouteKey())->update(
//            $toUpdate
//        );
//        return View::make('game/game')->with("games", $this->retrieveGames())->with("theGame", $game->where('id', $game->getRouteKey())->first())->with("cont_updated", true);
//        $game->save();
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Game  $game
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Game $game)
    {
        dd("Are you trying to hack us?".$_SERVER['REMOTE_ADDR']);
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
//        Game::save($toUpdate);
    }

    /**
     * Display the specified resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return View::make('game/game')->with("games", $this->retrieveGames())->with("theGame", $game);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return View::make('game/game')->with("games", $this->retrieveGames())->with("theGame", $game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param   Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(GameRequest $request, Game $game) //have to update it to my request
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
        $game->where('id', $game->getRouteKey())->update(
//        Game::where('id', $game->getRouteKey())->update(
            $toUpdate
        );
        return View::make('game/game')->with("games", $this->retrieveGames())->with("theGame", $game->where('id', $game->getRouteKey())->first())->with("cont_updated", true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->where('id', $game->getRouteKey())->delete();
//        return View::make('game/game')->with("games", $this->retrieveGames());
        return redirect('/manage/game');
    }
}
