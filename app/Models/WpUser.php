<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpUser extends Model
{

    protected $table = 'wp_users';

    protected $primaryKey = 'ID';

    protected $guarded = ['ID'];

    public $timestamps = false;

}
