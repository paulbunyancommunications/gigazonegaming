<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Player
 * @package App\Models\Championship
 */
class Player extends Model
{
    use PlayerRelationable;
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
        });
    }

    /**
     * Get player's teams
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teams()
    {
        return $this->morphedByMany('App\Models\Championship\Team', 'relation', 'player_relations', 'player_id', 'relation_id');
    }

    /**
     * @return mixed
     */
    public function getTeamsAttribute()
    {
        return $this->teams()->get();
    }

    /**
     * Get player's games
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function games()
    {
        return $this->morphedByMany('App\Models\Championship\Game', 'relation', 'player_relations', 'player_id', 'relation_id');
    }

    /**
     * @return mixed
     */
    public function getGamesAttribute()
    {
        return $this->games()->get();
    }

    /**
     * Get the tournaments the user is in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tournaments()
    {
        return $this->morphedByMany('App\Models\Championship\Tournament', 'relation', 'player_relations', 'player_id', 'relation_id');
    }

    /**
     * @return mixed
     */
    public function getTournamentsAttribute()
    {
        return $this->tournaments()->get();
    }

    /**
     * A player has a user account
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\Users\User');
    }

    /**
     * @return mixed
     */
    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphTo
     */
    public function playerRelations()
    {
        return $this->playerRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames();
    }
    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphTo
     */
    public static function playersRelations()
    {
        return PlayerRelationable::playersRelationsToAnArrayOfObjectsOfTeamsAndTournamentsAndGames();
    }
    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphTo
     */
    public function getThisPlayerInfoBy($parameter = [])
    {
        if (isset($this->id)) {
            $parameter['player'] = $this->id;
        }
        $playerInfo = $this->getPlayersInfoBy($parameter);
        if ($playerInfo!= null and $playerInfo!= '' and $playerInfo!= []) {
            return $playerInfo[0];
        }
        return $playerInfo;
    }
}
