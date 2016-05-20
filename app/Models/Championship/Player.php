<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'mysql_champ';
    
    protected $fillable = ['username','email','phone','updated_by','updated_on'];

    public function team()
    {
        return $this->belongsTo('App\Models\Championship\Team');
    }
}
