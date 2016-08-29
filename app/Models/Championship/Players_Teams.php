<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Players_Teams extends Model
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
    protected $table = 'players_teams';


    /**
     * @var array
     */
    protected $fillable = ['player_id', 'team_id', 'verification_code'];

}
