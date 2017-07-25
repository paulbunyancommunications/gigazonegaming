<?php

namespace App\Models\Auth;

use App\Models\Championship\Player;
use Illuminate\Database\Eloquent\Model;

class PlayerUpdate extends Model
{

    public static function generateUser($request){
        if(!Users\User::where('email',$request->email)->first()) {
            $password = str_random(8);
            \Sentinel::register(['email' => $request->email, 'password' => $password]);
            if(!Player::where('email',$request->email)->first()){
                Player::create([
                    'email' => $request->email
                ]);
            }
            \Mail::raw('Here is your temporary password: ' . $password, function ($message) use ($request) {
                $message->to($request->email);
            });
            return view('/playerUpdate/register')->withSuccess('Check Your Email!');
        }
        return view('/playerUpdate/register')->withSuccess('')->withErrors('User already exists');
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user(){
        return $this->belongsTo(Player::class);
    }

    public static function updateInfo($request){
        $token = Player::where('email',$request->email)->first();
        $token->name = $request->name;
        $token->username = $request->username;
        $token->phone = $request->phone;
        $token->save();
        return redirect ('player/playerUpdate');
    }
}
