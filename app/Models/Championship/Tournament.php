<?php

namespace App\Models\Championship;

use App\Models\Championship\Relation\PlayerRelation;
use App\Models\Championship\Relation\PlayerRelationable;
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
    protected $fillable = [
        'name',
        'title',
        'max_players',
        'max_teams',
        'updated_by',
        'updated_on',
        'game_id',
        'overflow',
        'sign_up_open',
        'sign_up_close',
        'occurring',
        'sign_up_form',
        'sign_up_form_shortcode',
        'overflow'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'sign_up_open',
        'sign_up_close',
        'occurring'
    ];

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        // cause a delete of a tournament to cascade to children so they are also deleted

        static::deleting(function ($tournament) {
            /** @var Tournament $tournament */
            $hasTable = false;
            if(\Schema::connection('mysql_champ')->hasTable('player_relations')) {
                $hasTable = true;
            }
            if ($tournament) {
                $teams = $tournament->teams();
                foreach ($teams as $team) {
                    $team->delete();
                }
                if($hasTable) {
                    if (
                    PlayerRelation::where([
                        ["relation_id", "=", $tournament->id],
                        ["relation_type", "=", Tournament::class],
                    ])->exists()
                    ) {
                        PlayerRelation::where([
                            ["relation_id", "=", $tournament->id],
                            ["relation_type", "=", Tournament::class],
                        ])->delete();
                    }
                }
            }


        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getGameAttribute()
    {
        return $this->game()->first();
    }

    /**
     * Get tournament which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function playerRelation()
    {
        return $this->morphMany('App\Models\Championship\Relation\PlayerRelationable', 'relation');
    }

    /**
     * Get teams in this tournament
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Championship\Team');
    }

    /**
     * @return mixed
     */
    public function getTeamsAttribute()
    {
        return $this->teams()->get();
    }

}
