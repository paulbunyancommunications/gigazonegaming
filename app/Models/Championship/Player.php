<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'mysql_champ';
    
    protected $fillable = ['username','email','phone','name', 'team_id'];

    public function team()
    {
        return $this->belongsTo('App\Models\Championship\Team');
    }
}
