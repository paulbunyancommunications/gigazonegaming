<?php

namespace App\Model\Championship;

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

    /**
     * Get game which team is playing in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }

    /**
     * Get team captain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function captain()
    {
        return $this->belongsTo('App\Models\Championship\Player');
    }
    
    public function players()
    {
        return $this->hasMany('App\Models\Championship\Player');
    }
}
