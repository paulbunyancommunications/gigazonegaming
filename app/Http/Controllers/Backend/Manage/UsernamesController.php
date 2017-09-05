<?php

namespace App\Http\Controllers\Backend\Manage;

use App\Http\Controllers\Controller;
use App\Models\Auth\Users\User;
use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Tournament;
use App\Models\Championship\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class UsernamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('game/Usernames');
    }

    public function createUsernameAdmin(Request $request){
        $player = new Username();
        $player->username = $request->username;
        $player->avatar_url = $request->avatar_url;
        $player->player_id = $request->player_id;
        $player->tournament_id = $request->tournament_id;
        $player->save();
        return Redirect::back()->with('success', "The players Username was successfully added");

    }
    public function createUsernameUser(Request $request){
        if(User::where('email',$request->email)->first() || Player::where('email',$request->email)->first()) {
            $player = new Username();
            $player->username = $request->username;
            $player->avatar_url = $request->avatar_url;
            $playerID = Player::where('email', $request->email)->first()->pluck('id');
            $player->player_id = $playerID;
            $tournamentID = PlayerRelation::where('player_id', $playerID)->where('relation_type', 'App\Models\Championship\Tournament')->first()->pluck('relation_id');
            $player->tournament_id = $tournamentID;
            $player->save();
            return Redirect::back()->with('success', "The players Username was successfully added");
        }
        return Redirect::back();

    }
    public function getUsername(Request $request){
        $user = Username::where('player_id',$request->player_id)->where('tournament_id',$request->tournament_id)->first();
        return $user;
    }
}
