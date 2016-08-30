<?php
/**
 * EloquentActivation
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentActivation model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Activations
 */

namespace App\Models\Auth\Activations;

use Cartalyst\Sentinel\Activations\EloquentActivation;

class Activation extends EloquentActivation
{
    protected $connection = "mysql_champ";
    protected $table = "activations";
}
