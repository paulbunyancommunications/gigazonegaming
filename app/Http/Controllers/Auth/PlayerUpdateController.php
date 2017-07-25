<?php

namespace App\Http\Controllers\Auth;
Use App\Models\Auth\AuthenticateUser;
use App\Models\Auth\RegisterUser;
use App\Http\Controllers\Controller;
use App\Models\Auth\UpdatePlayerInfo;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;

class PlayerUpdateController extends Controller
{
    /*This is used to display the login page*/
    public function login(){
        $email="";
        return view('/playerUpdate/login')->withEmail($email)->withSuccess("");
    }

    /*After login is clicked this determines the authentication of the user returns a redirect*/
    public function postLogin(AuthenticateUser $auth)
    {
        return $auth->login();
    }

    /*Used to display the register page*/
    public function register(){
        return view('/playerUpdate/register')->withSuccess($success = "");
    }

    /*After register is clicked this registers a user with their email returns a redirect*/
    public function postRegister(RegisterUser $auth)
    {
        return $auth->register();
    }

    /*When player is redirected to their profile this returns a view with all the players relations if they have any*/
    public function playerUpdate()
    {
        $tournaments=[];
        $teams=[];
        $games=[];
        if($user = \Sentinel::getUser()){
            $token = Player::where('email',$user->email)->first();
            $teamRelations = PlayerRelation::where('player_id',$token->id)->where('relation_type','App\Models\Championship\Team')->get();
            $tournamentRelations = PlayerRelation::where('player_id',$token->id)->where('relation_type','App\Models\Championship\Tournament')->get();
            $gameRelations = PlayerRelation::where('player_id',$token->id)->where('relation_type','App\Models\Championship\Game')->get();
            for($i=0;$i<count($teamRelations);$i++){
                array_push($teams,Team::where('id',$teamRelations[$i]->relation_id)->first());
            }
            for($i=0;$i<count($tournamentRelations);$i++){
                array_push($tournaments,Tournament::where('id',$tournamentRelations[$i]->relation_id)->first());
            }
            for($i=0;$i<count($gameRelations);$i++){
                array_push($games , Game::where('id',$gameRelations[$i]->relation_id)->first());
            }
            return view('/playerUpdate/playerUpdate')->withToken($token)
                ->withTeams($teams)
                ->withTournaments($tournaments)
                ->withGames($games);
        }
        return redirect('/player/login')->withErrors("Authorization Needed")->withEmail($email='');
    }

    /*This is activated when the player clicks update on their profile*/
    public function postUpdate(UpdatePlayerInfo $auth){
        return $auth->update();
    }

    /*This is to logout a user when they click logout on their profile*/
    public function logout(){
        if($user = \Sentinel::getUser()){
            \Sentinel::logout($user, true);
            return view('/playerUpdate/login')->withSuccess("Successfully Logged Out!")->withEmail($email = "");
        }
        return redirect('/player/login')->withErrors("Something Went Wrong! Not Logged Out.");
    }

    /*This is the beginning stages of how to recover a password if forgotten for the player update form*/
    public function recover(){
        return view('/playerUpdate/recover');
    }

}
