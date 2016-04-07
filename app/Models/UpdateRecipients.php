<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateRecipients extends Model
{
    protected $table = "update_recipients";

    protected $fillable = [
        'email', 'participate',
    ];

}
