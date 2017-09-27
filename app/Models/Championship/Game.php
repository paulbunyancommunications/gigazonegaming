<?php

namespace App\Models\Championship;

use App\Models\Championship\Relation\PlayerRelation;
use Cocur\Slugify\Slugify;
use Illuminate\Database\Eloquent\Model;
use App\Models\Championship\Relation\PlayerRelationable;

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
     * Guard the ID
     * @var array
     */
    protected $guarded = ['id'];

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
            $hasTable = false;
            if(\Schema::connection('mysql_champ')->hasTable('player_relations')) {
                $hasTable = true;
            }
//        dd($game);
            if ($game) {
                $tournaments = $game->tournaments();
                foreach ($tournaments as $tournament){
                    $tournament->delete();
                }
                if($hasTable) {
                    if (PlayerRelation::where([
                        ["relation_id", "=", $game->id],
                        ["relation_type", "=", Game::class],
                    ])->exists()
                    ) {
                        PlayerRelation::where([
                            ["relation_id", "=", $game->id],
                            ["relation_type", "=", Game::class],
                        ])->delete();
                    }
                }
                $game->delete();
            }

        });
    }

    /**
     * return column list
     *
     * @return array
     */
    public function columns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * Set name value to slug
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $slugify = Slugify::create();
        $this->attributes['name'] = $slugify->slugify($value);
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
     * @return mixed
     */
    public function getTournamentsAttribute()
    {
        return $this->tournaments()->get();
    }

    /**
     * Get tournaments playing this game
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
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
