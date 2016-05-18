<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App\Model\Championship
 */
class Team extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['username','email','phone','parent_id'];

    public static function boot()
    {
        parent::boot();

        // cause a delete of a team to cascade to children so they are also deleted
        static::deleted(function ($team) {
            $team->players()->delete();
        });
    }
    
    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament()
    {
        return $this->belongsTo('App\Models\Championship\Tournament');
    }

    /**
     * Get team captain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function captain()
    {
        return $this->players()->where('captain', '=', 1);
    }

    public function players()
    {
        return $this->hasMany('App\Models\Championship\Player');
    }
}
