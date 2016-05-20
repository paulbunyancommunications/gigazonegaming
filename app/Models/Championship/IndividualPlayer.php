<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IndividualPlayer
 * @package App\Model\Championship
 */
class IndividualPlayer extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
<<<<<<< HEAD
    protected $fillable = ['username', 'email', 'phone', 'game_id','updated_by','updated_on'];
=======
    protected $fillable = ['username', 'email', 'phone', 'name', 'game_id'];
>>>>>>> b5e78b393775e5d3b63516a2dfdfdda32a228eb4

    /**
     * Get game the individual player want to be in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Championship\Game');
    }
}
