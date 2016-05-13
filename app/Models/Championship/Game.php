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
     * Get teams playing this game
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Championship\Team');
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

    public function scopeByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }
}
