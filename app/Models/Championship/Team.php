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
        $tournament = $this->tournament()->first();
        return Game::
            join('tournaments','games.id', '=', 'tournaments.game_id')
            ->leftJoin('teams','tournaments.id', '=', 'teams.tournament_id')
            ->where('tournaments.id', '=', $tournament->id)
            ->where('games.id', '=', $tournament->game_id)
            ->where('teams.id', '=', $this->id)
            ->select('games.*')
            ;
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

        $teamId = $this->id;
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

        $teamId = $this->id;
        $maxPlayers = $this->tournament()->select('max_players')->first()->toArray();
        $teamCount = PlayerRelation::where('relation_id', '=', $this->id)->where('relation_type', '=', PlayerRelationable::getTeamRoute())->count();
//        dd($teamCount);
        return $maxPlayers["max_players"] > $teamCount; //if team is full this will eval to true, otherwise will eval to false
    }
//    public function addPlayer(Player $player){
//        $tournament = $this->tournament;
//        PlayerRelationable::addPlayer();
//        PlayerRelationable::addPlayer();
//        return true;
//    }

}
