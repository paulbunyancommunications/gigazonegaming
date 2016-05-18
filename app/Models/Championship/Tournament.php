<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['name', 'game_id'];

    public static function boot()
    {
        parent::boot();

        // cause a delete of a tournament to cascade to children so they are also deleted
        static::deleted(function ($tournament) {
            $tournament->teams()->delete();
        });
    }

    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }

    public function teams()
    {
        return $this->hasMany('App\Models\Championship\Team');
    }

 
}
