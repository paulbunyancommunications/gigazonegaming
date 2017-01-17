<?php
/**
 * EloquentThrottle
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentThrottle model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Throttling
 */

namespace App\Models\Auth\Throttling;

use Cartalyst\Sentinel\Throttling\EloquentThrottle;

class Throttle extends EloquentThrottle
{
    protected $connection = "mysql_sentinel";
    protected $table = "throttle";
}
