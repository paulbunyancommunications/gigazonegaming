<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpUser extends Model
{

    protected $table = 'wp_users';

    protected $primaryKey = 'ID';

    protected $guarded = ['ID'];

    public $timestamps = false;

    /**
     * Set the password value to md5, WP itself will hash if md5 is found
     * @param $value
     */
    public function setUserPassAttribute($value)
    {
        $this->attributes['user_pass'] = md5($value);
    }
}
