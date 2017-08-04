<?php
/**
 * EloquentUser
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentUser model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Users
 */

namespace App\Models\Auth\Users;

use App\Models\Championship\Player;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Facades\Hash;

class User extends EloquentUser
{
    protected $connection = "mysql_sentinel";
    protected $table = "users";
    protected static $rolesModel        = 'App\Models\Auth\Roles\Role';
    protected static $persistencesModel = 'App\Models\Auth\Persistences\Persistence';
    protected static $activationsModel  = 'App\Models\Auth\Activations\Activation';
    protected static $remindersModel    = 'App\Models\Auth\Reminders\Reminder';
    protected static $throttlingModel   = 'App\Models\Auth\Throttling\Throttle';


    public static function boot()
    {
        parent::boot();

        // cause a delete of a team to cascade to children so they are also deleted
        static::deleting(function ($user) {
            $player = $user->player();
            if ($player) {
                $player->delete();
            }
        });
    }
    
    /**
     * A player has a user account
     */
    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public static function byEmail($email,$password){
        $user = Player::where('email', $email)->first();
        if(Hash::check($password, $user->password)){
            return $user;
        }
        return false;
    }
}
