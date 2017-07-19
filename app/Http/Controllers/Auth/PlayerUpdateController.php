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
        return view('/auth/login')->withEmail($email);
    }
    public function postLogin(AuthenticateUser $auth)
    {
        return $auth->login();
    }

    public function register(){
        $message= "";
        return view('/auth/register')->withMessage($message);
    }
    public function postRegister(RegisterUser $auth)
    {
        return $auth->register();
    }

    public function playerUpdate()
    {
        if($user = \Sentinel::getUser()){
            $token = Player::where('email',$user->email)->first();
            return view('/auth/playerUpdate')->withToken($token);
        }
        return redirect()->back()->withErrors("Authorization Needed")->withEmail($email='');

    }

}
