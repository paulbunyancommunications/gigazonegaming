<?php

namespace App\Models\Auth;

use App\Models\Championship\Player;
use Illuminate\Database\Eloquent\Model;

class PlayerUpdate extends Model
{
    /**
     * This creates a user if not already created
     * registers a user with sentinel
     * creates a reminder code for the user
     * mails the user a link to create a password
     * returns a redirect response
     */
    public static function generateUser($request){
        if(!Users\User::where('email',$request->email)->first()) {
            $password = \Hash::make('password');
            $user = \Sentinel::register(['email' => $request->email,'password'=> $password]);
            $reminder = \Reminder::create($user);
            if(!Player::where(['email' => $request->email, 'username' => $request->username])->first()){
                Player::create([
                    'username' => $request->username,
                    'email' => $request->email
                ]);
            }
            $info = ['token'=> $reminder->code,'data'=>'Click here to be redirected and create a password!'];
            \Mail::send('/playerUpdate/email',$info, function ($message) use ($request) {
                $message->to($request->email);
            });
           return redirect('/player/register')->with('success','Check Your Email!');
        }
        return redirect('/player/register')->withErrors('User already exists');
    }
    public static function createNewPassword($request){
        if($user = Users\User::where('email',$request->email)->first()) {
            $password = \Hash::make('password');
            $user->password = $password;
            $user->save();
            $reminder = \Reminder::create($user);
            $info = ['token'=> $reminder->code,'data'=>'Click here to be redirected and create a password!'];
            \Mail::send('/playerUpdate/email',$info, function ($message) use ($request) {
                $message->to($request->email);
            });
            return redirect('/player/recover')->with('success','Check Your Email!');
        }
        return redirect('/player/recover')->withErrors('User does not exist!');
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function user(){
        return $this->belongsTo(Player::class);
    }

    /**
     * This updates a players attributes
     * returns a redirect back to page
     */
    public static function updateInfo($request){
        $token = Player::where('email',$request->email)->first();
        $token->name = $request->name;
        $token->username = $request->username;
        $token->phone = $request->phone;
        $token->save();
        return redirect('/player/playerUpdate');
    }

    /**
     * creates a password for the user that they entered
     * returns a redirect
     */
    public static function createPassword($request){
        $reminder = \Reminder::where('code',$request->token)->first();
        $user = Users\User::where('id',$reminder->user_id)->first();
        $user->password = \Hash::make($request->password);
        $user->save();
        $reminder->delete();
        return redirect('/player/login')->with('success',"Password Created! You Can Now Login!");
    }
}
