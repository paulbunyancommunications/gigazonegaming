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
    protected $fillable = ['token','game_id'];

    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }
}
