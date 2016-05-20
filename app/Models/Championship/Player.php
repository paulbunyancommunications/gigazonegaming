<?php

namespace App\Models\Championship;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'mysql_champ';
    
<<<<<<< HEAD
    protected $fillable = ['username','email','phone','updated_by','updated_on'];
=======
    protected $fillable = ['username','email','phone','name', 'team_id'];
>>>>>>> b5e78b393775e5d3b63516a2dfdfdda32a228eb4

    public function team()
    {
        return $this->belongsTo('App\Models\Championship\Team');
    }
}
