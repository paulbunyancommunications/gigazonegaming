<?php
namespace App\Traits\Championship;

/**
 * NotifyPlayerTrait
 *
 * Created 9/28/16 10:11 AM
 * Trait for notifications to players/captains
 *
 * @author Nate Nolting <naten@paulbunyan.net>
 * @package App\Traits\Championship
 */

use Pbc\FormMail\Traits\QueueTrait;
use Pbc\FormMail\Traits\SendTrait;
use Pbc\FormMail\Traits\MessageTrait;
use Pbc\FormMail\Helpers\FormMailHelper;
use Pbc\FormMail\FormMail;
use App\Models\Championship\Player;

trait NotifyPlayerTrait
{
    use QueueTrait, SendTrait, MessageTrait;
}

