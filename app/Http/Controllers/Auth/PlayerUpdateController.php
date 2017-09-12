<?php

namespace App\Http\Controllers\Auth;
Use App\Http\Middleware\AuthenticateUser;
use App\Http\Middleware\RecoverPassword;
use App\Http\Middleware\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Middleware\UpdatePlayerInfo;
use App\Http\Middleware\UserPassword;
use App\Models\Championship\Game;
use App\Models\Championship\Player;
use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Team;
use App\Models\Championship\Tournament;
use App\Models\Championship\Username;

class PlayerUpdateController extends Controller
{

    /*After login is clicked this determines the authentication of the user returns a redirect*/
    public function postLogin(AuthenticateUser $auth)
    {
        return $auth->login();
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
        $playersRelations=[];
        $players=[];
        $tournamentNames=[];
        if($user = \Sentinel::getUser()){
            $token = Player::where('email',$user->email)->first();
            $userNames = Username::where('player_id',$token->id)->get();
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
            for($i=0;$i<count($teams);$i++){
                if($token->id === $teams[$i]->captain){
                    array_push($playersRelations,PlayerRelation::where('relation_id',$teams[$i]->id)->where('relation_type','App\Models\Championship\Team')->get());
                    $players[$i] = array();
                    for($j=0;$j<count($playersRelations[$i]);$j++) {
                        array_push($players[$i], Player::where('id',$playersRelations[$i][$j]->player_id)->first());
                    }
                }
            }
            return view('/playerUpdate/playerUpdate')->withToken($token)
                ->withTeams($teams)
                ->withTournaments($tournaments)
                ->withGames($games)
                ->withPlayers($players)
                ->withUser_names($userNames);
        }
        return redirect('/player/login')->withErrors("Authorization Needed")->withEmail('');
    }

    /*This is activated when the player clicks update on their profile*/
    public function postUpdate(UpdatePlayerInfo $auth){
        return $auth->update();
    }

    /*This is to logout a user when they click logout on their profile*/
    public function logout(){
        if($user = \Sentinel::getUser()){
            \Sentinel::logout($user, true);
            return redirect('/player/login')->with('success',"Successfully Logged Out!")->withEmail("");
        }
        return redirect('/player/login')->withErrors("Something Went Wrong! Not Logged Out.");
    }

    public function postRecover(RecoverPassword $auth){

        return $auth->recovery();
    }

    /*This is how a user creates a password*/
    public function createPassword(UserPassword $auth ){
        return $auth->createPassword();
    }

}
