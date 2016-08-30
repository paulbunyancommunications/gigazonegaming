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

use Cartalyst\Sentinel\Users\EloquentUser;

class User extends EloquentUser
{
    protected $connection = "mysql_champ";
    protected $table = "users";


    /**
     * A player has a user account
     */
    public function player()
    {
        return $this->belongsToMany(\App\Models\Championship\Player::class);
    }

    public function playerAttribute()
    {
        return $this->player()->first();
    }
}
