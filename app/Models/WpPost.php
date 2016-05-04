<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpPost extends Model
{
    protected $table = 'wp_posts';

    protected $primaryKey = 'ID';
    
    protected $guarded = ['ID'];

    public $timestamps = false;
}
