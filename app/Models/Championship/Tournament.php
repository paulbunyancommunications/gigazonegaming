<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tournament
 * @package App\Models\Championship
 */
class Tournament extends Model
{
    use PlayerRelationable;
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name', 'game_id', 'max_players','updated_by','updated_on'];

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        // cause a delete of a tournament to cascade to children so they are also deleted

        static::deleting(function ($tournament) {

            /** @var Tournament $tournament */
            $tournament->teams()->delete();
            $tournament->findPlayersRelations()->delete(); //this should delete the relations not the players

        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game', 'game_id', 'id');
    }


    /**
     * Get teams in this tournament
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Championship\Team');
    }
}
