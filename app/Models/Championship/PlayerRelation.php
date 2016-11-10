<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class PlayerRelation extends Model
{
    use PlayerRelationable;
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
}
