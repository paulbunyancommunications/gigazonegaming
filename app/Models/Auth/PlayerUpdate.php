<?php

namespace App\Models\Auth;

use App\Models\Championship\Player;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PlayerUpdate extends Model
{

    public static function generateUser($request){
        if(!Player::where('email',$request->email)->first()) {
            \Sentinel::registerAndActivate(['email'=>$request->email,'password'=>$request->password]);
            Player::create([
                'phone' => $request->phone,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password)
            ]);
            return "Login";
        }
        return "This Player already has an Account";
    }
    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user(){
        return $this->belongsTo(Player::class);
    }
}
