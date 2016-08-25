<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpPostMeta extends Model
{
    protected $table = 'wp_postmeta';
    protected $primaryKey = 'meta_id';
    protected $guarded = ['meta_id'];
    public $timestamps = false;
}
