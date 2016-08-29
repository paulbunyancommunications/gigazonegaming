<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Players_Tournaments extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'players_tournaments';

    /**
     * @var array
     */
    protected $fillable = ['player_id', 'tournament_id'];
}
