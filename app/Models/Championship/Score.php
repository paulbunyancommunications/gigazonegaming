<?php

namespace App\Models\Championship;

use App\Models\Championship\Tournament;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship\Relation\PlayerRelationable;

/**
 * Class LeaderBoard
 * @package App\Models\Championship
 */
class Score extends Model
{
    use PlayerRelationable;

    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['player', 'tournament', 'score'];

    /**
     * @return mixed
     */
    public function tournament()
    {
        return Tournament::find($this->attributes['tournament']);
    }

    /**
     * @return mixed
     */
    public function getTournamentAttribute()
    {
        return $this->tournament();
    }

    /**
     * @return mixed
     */
    public function player()
    {
        return \App\Models\Championship\player::find($this->attributes['player']);
    }

    /**
     * @return mixed
     */
    public function getPlayerAttribute()
    {
        return $this->player();
    }
}
