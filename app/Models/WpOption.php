<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpOption extends Model
{
    protected $table = 'wp_options';

    protected $primaryKey = 'option_id';
    
    protected $guarded = ['option_id'];

    public $timestamps = false;
}
