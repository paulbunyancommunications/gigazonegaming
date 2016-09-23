<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameRequest;
use App\Models\Championship\Game;
use App\Models\Championship\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/game');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function store(GameRequest $request)
    {
        \DB::beginTransaction();
        try {
            $game = new Game();
            $game->uri = $request['uri'];
            $game->description = $request['description'];
            $game->name = $request['name'];
            $game->title = $request['title'];
            $game->updated_by = $this->getUserId();
            $game->updated_on = Carbon::now("CST");
            $game->created_at = Carbon::now("CST");
            $game->updated_at = Carbon::now("CST");
            $game->save();
            \DB::commit();
            return redirect('/manage/game/edit/' . $game->id)->with('success', "The game ".$request['title']." was added!");
        } catch (\Exception $ex) {
            \DB::rollback();
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Game  $game
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return View::make('game/game')->with("theGame", $game);
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
        // setup updated on and by
        $toUpdate = [
            'updated_by' => $this->getUserId(),
            'updated_on' => Carbon::now("CST"),
        ];

        // filter out all request items by the columns in the games table
        $columns = $game->columns();
        $inputs = array_filter($request->all(), function ($k) use ($columns) {
            return in_array($k, $columns);
        }, ARRAY_FILTER_USE_KEY);

        // merge in the found filtered items
        $toUpdate = array_merge($toUpdate, $inputs);
        try {
            $game->where('id', $game->getRouteKey())->update(
                $toUpdate
            );
            return Redirect::back()
                ->with("theGame", $game->where('id', $game->getRouteKey())->first())
                ->with("success", "Game ".$toUpdate['title']." was updated!");
        } catch (\Exception $ex) {
            return redirect()->back()->withInput()->with('error', $ex->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $name = $game->name;
        foreach (Tournament::where('game_id', '=', $game->getRouteKey())->get() as $key => $tounament) {
            foreach (Team::where('tournament_id', '=', $tounament->id)->get() as $k => $team){
                PlayerRelation::where('relation_id', '=', $team->id)->where('relation_type', '=', Team::class)->delete();
                $team->delete();
            }
            PlayerRelation::where('relation_id', '=', $tounament->id)->where('relation_type', '=', Tournament::class)->delete();
            $tounament->delete();
        }
        PlayerRelation::where('relation_id', '=', $game->getRouteKey())->where('relation_type', '=', Game::class)->delete();
        $game->where('id', $game->getRouteKey())->delete();

        return View::make('game/game')
            ->with("success", "The game ".$name." was successfully deleted (and all tournaments, teams and relations attached to it).");
    }
}
