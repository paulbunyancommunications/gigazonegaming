<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpLink extends Model
{
    protected $table = 'wp_links';

    protected $primaryKey = 'link_id';

    protected $guarded = ['link_id'];

    public $timestamps = false;
}
