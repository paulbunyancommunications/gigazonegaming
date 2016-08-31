<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'mysql_champ';

    protected $fillable = ['username','email','phone','updated_by','updated_on'];

//    /**
//     * Get player's team
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function team()
//    {
//        return $this->belongsToMany('App\Models\Championship\Team');
//    }

    /**
     * Get player's team
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsToMany(\App\Models\Championship\Players_Teams::class);
}

    public function teamAttribute()
    {
        return $this->team();
    }

    public function tournament()
    {
        return $this->belongsToMany(\App\Models\Championship\Players_Tournaments::class);
    }

    public function tournamentAttribute()
    {
        return $this->tournament();
    }

    /**
     * A player has a user account
     */
    public function user()
    {
        return $this->belongsToMany(\App\Models\Championship\User::class);
    }

    public function userAttribute()
    {
        return $this->user()->first();
    }


}
