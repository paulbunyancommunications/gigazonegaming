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
    protected $fillable = ['name','description','uri','updated_by','updated_on'];

    /**
     * Bootup model
     */
    public static function boot()
    {
        parent::boot();

        // cause a delete of a game to cascade to children so they are also deleted
        static::deleting(function ($game) {
            $game->tournaments()->delete();
            $game->individualPlayers()->delete();

        });
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
    public function players()
    {
        return $this->hasMany('App\Models\Championship\Player');
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
