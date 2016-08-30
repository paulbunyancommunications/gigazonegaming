<?php
/**
 * EloquentRole
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentRole model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Roles
 */

namespace App\Models\Auth\Roles;

use Cartalyst\Sentinel\Roles\EloquentRole;

class Role extends EloquentRole
{
    protected $connection = "mysql_champ";
    protected $table = "roles";

}
