<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8/30/17
 * Time: 2:25 PM
 */

namespace App\Models\Championship;


use Illuminate\Database\Eloquent\Model;

class Username extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mysql_champ';

    /**
     * @var array
     */
    protected $fillable = ['username', 'avatar_url', 'player_id', 'tournament_id'];

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

    }
}