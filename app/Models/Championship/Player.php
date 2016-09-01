<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 * @package App\Models\Championship
 */
class Player extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['username', 'email', 'phone', 'updated_by', 'updated_on'];

    public static function boot()
    {
        parent::boot();

        // when deleted
        static::deleting(function ($player) {
            $player->teams()->detach();
            $player->games()->detach();
            $player->tournaments()->detach();
        });
    }

    /**
     * Get player's teams
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teams()
    {
        return $this->belongsToMany('App\Models\Championship\Team');
    }

    /**
     * Get player's games
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function games()
    {
        return $this->belongsToMany('App\Models\Championship\Game');
    }

    /**
     * Get the tournaments the user is in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tournaments()
    {
        return $this->belongsToMany('App\Models\Championship\Tournament');
    }

    /**
     * A player has a user account
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\Users\User');
    }
}
