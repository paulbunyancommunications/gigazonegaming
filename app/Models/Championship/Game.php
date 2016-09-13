<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Game
 * @package App\Model\Championship
 */
class Game extends Model
{
    use PlayerRelationable;
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
            $game->tournaments()->detach();
            $game->players()->detach();

        });
    }

    /**
     * Get tournaments playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tournaments()
    {
        return $this->hasMany('App\Models\Championship\Tournament', 'game_id', 'id');
    }
    /**
     * Get tournaments playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasManyThrough('App\Models\Championship\Team', 'App\Models\Championship\Tournament');
    }

    /**
     * Get tournaments playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function game_player()
//    {
//        return $this->hasMany('App\Models\Championship\Game_Player', 'game_id', 'id');
//    }

    /**
     * Get all the players for the current game
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany('App\Models\Championship\Player');
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
