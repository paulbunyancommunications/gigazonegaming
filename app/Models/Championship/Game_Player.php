<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Game_Player extends Model
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
    protected $table = 'game_player';

    /**
     * @var array
     */
    protected $fillable = ['player_id', 'game_id'];

    /**
     * Get player's team
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function games()
    {
        return $this->belongsTo('App\Models\Championship\Games', 'id', 'game_id');
    }
    /**
     * Get player's team
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function players()
    {
        return $this->belongsTo('App\Models\Championship\Players', 'id', 'player_id');
    }

}
