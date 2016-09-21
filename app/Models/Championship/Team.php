<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App\Model\Championship
 */
class Team extends Model
{
    use PlayerRelationable;
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name', 'emblem', 'captain', 'tournament_id','updated_by','updated_on'];

    /**
     * Get game which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->tournament()->first()->game;
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\Championship\Tournament','tournament_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getTournamentAttribute()
    {
        return $this->tournament()->first();
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function playerRelation()
    {
        return $this->morphMany('App\Models\Championship\PlayerRelationable', 'relation');
    }

    /**
     * Get team captain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function captain()
    {
        if (!$this->getAttribute('captain')) {
            return false;
        }
        return Player::findOrFail($this->getAttribute('captain'));
    }

    /**
     * Get true if team is full or false if you can still add players
     *
     * @return boolean
     */
    public function isTeamFull(){

        $maxPlayers = $this->tournament()->select('max_players')->first()->toArray();
        $teamCount = PlayerRelation::where('relation_id', '=', $this->id)->where('relation_type', '=', PlayerRelationable::getTeamRoute())->count();

        return $maxPlayers["max_players"] <= $teamCount; //if team is full this will eval to true, otherwise will eval to false
    }
    /**
     * Get true if team is not full or false if you can't add players
     *
     * @return boolean
     */
    public function isTeamNotFull(){

        $maxPlayers = $this->tournament()->select('max_players')->first()->toArray();
        $teamCount = PlayerRelation::where('relation_id', '=', $this->id)->where('relation_type', '=', PlayerRelationable::getTeamRoute())->count();
        return $maxPlayers["max_players"] > $teamCount; //if team is full this will eval to true, otherwise will eval to false
    }

}
