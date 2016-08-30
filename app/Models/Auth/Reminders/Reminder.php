<?php
/**
 * EloquentReminder
 *
 * Created 8/26/16 12:07 PM
 * Extends EloquentReminder model from Cartalyst Sentinel
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Models\Auth\Reminders
 */

namespace App\Models\Auth\Reminders;

use Cartalyst\Sentinel\Reminders\EloquentReminder;

class Reminder extends EloquentReminder
{
    protected $connection = "mysql_champ";
    protected $table = "reminders";
}
