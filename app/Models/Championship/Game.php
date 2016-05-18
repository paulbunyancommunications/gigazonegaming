<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App\Model\Championship
 */
class Game extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name','description','uri'];

    /**
     * Bootup model
     */
    public static function boot()
    {
        parent::boot();

        // cause a delete of a game to cascade to children so they are also deleted
        static::deleted(function ($game) {
            $game->tournaments()->delete();
            $game->teams()->delete();
            $game->individualPlayers()->delete();
        });
    }
    /**
     * Get teams playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Championship\Team');
    }

    /**
     * Get teams playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments()
    {
        return $this->hasMany('App\Models\Championship\Tournament');
    }

    /**
     * Get Individual Players who what to play this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function individualPlayers()
    {
        return $this->hasMany('App\Models\Championship\IndividualPlayer');
    }

    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }
}
