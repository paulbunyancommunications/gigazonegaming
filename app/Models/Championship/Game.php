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
            $game->tournaments()->delete();

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
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }
    /**
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeById($query, $name)
    {
        return $query->where('name', $name)->first();
    }
}
