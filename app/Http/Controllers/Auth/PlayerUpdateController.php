<?php

namespace App\Http\Controllers\Auth;
Use App\Models\Auth\AuthenticateUser;
use App\Models\Auth\RegisterUser;
use App\Http\Controllers\Controller;
use App\Models\Championship\Player;

class PlayerUpdateController extends Controller
{
    public function login(){
        $email="";
        return view('/playerUpdate/login')->withEmail($email)->withSuccess("");
    }
    public function postLogin(AuthenticateUser $auth)
    {
        return $auth->login();
    }

    public function register(){
        $message= "";
        return view('/playerUpdate/register')->withMessage($message);
    }
    public function postRegister(RegisterUser $auth)
    {
        return $auth->register();
    }

    public function playerUpdate()
    {
        if($user = \Sentinel::getUser()){
            $token = Player::where('email',$user->email)->first();
            return view('/playerUpdate/playerUpdate')->withToken($token);
        }
        return redirect('/player/login')->withErrors("Authorization Needed")->withEmail($email='');

    }
    public function logout(){
        if($user = \Sentinel::getUser()){
            \Sentinel::logout($user, true);
            return view('/playerUpdate/login')->withSuccess("Successfully Logged Out!")->withEmail($email = "");
        }
        return redirect('/player/login')->withErrors("Something Went Wrong! Not Logged Out.");
    }

}
