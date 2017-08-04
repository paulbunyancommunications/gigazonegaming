<?php

namespace App\Http\Controllers\Auth;
Use App\Http\AuthenticateUser;
use App\Http\RegisterUser;
use App\Http\Controllers\Controller;
use App\Models\Championship\Player;

class PlayerUpdateController extends Controller
{
    public function login(){
        $email="";
        return view('/LeagueOfLegends/login')->withEmail($email);
    }
    public function postLogin(AuthenticateUser $auth)
    {
        return $auth->login();
    }

    public function register(){
        $message= "";
        return view('/LeagueOfLegends/register')->withMessage($message);
    }
    public function postRegister(RegisterUser $auth)
    {
        return $auth->register();
    }

    public function playerUpdate()
    {
        if($user = \Sentinel::getUser()){
            $token = Player::where('email',$user->email)->first();
            return view('/LeagueOfLegends/playerUpdate')->withToken($token);
        }
        return redirect()->back()->withErrors("Authorization Needed")->withEmail($email='');

    }

}
