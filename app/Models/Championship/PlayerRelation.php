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
     * A player has a user account
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Auth\Users\User', 'user_id');
    }
}
