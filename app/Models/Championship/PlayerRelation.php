<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class PlayerRelation extends Model
{

    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['player_id', 'relation_id', 'relation_type'];

    public static function boot()
    {
        parent::boot();

    }

    /**
     * Get player's teams
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation()
    {
        return $this->belongsToMany('App\Models\Championship\Team');
    }

    /**
     * Get player's games
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function games()
    {
        return $this->belongsToMany('App\Models\Championship\Game');
    }

    /**
     * Get the tournaments the user is in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tournaments()
    {
        return $this->belongsToMany('App\Models\Championship\Tournament');
    }

    /**
     * A player has a user account
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\Users\User');
    }
}
