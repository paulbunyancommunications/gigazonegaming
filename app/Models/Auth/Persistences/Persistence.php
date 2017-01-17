<?php
/**
 * EloquentPersistence
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentPersistence model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Persistences
 */

namespace App\Models\Auth\Persistences;

use Cartalyst\Sentinel\Persistences\EloquentPersistence;

class Persistence extends EloquentPersistence
{
    protected $connection = "mysql_sentinel";
    protected $table = "persistences";
    protected static $usersModel = 'App\Models\Auth\Users\User';
}
