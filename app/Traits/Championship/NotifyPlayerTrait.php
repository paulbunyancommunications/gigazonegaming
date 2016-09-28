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

use App\Http\Requests\IndividualPlayerRequest;
use Pbc\Bandolier\Type\Strings;
use Pbc\FormMail\Http\Controllers\FormMailController;
use Pbc\FormMail\Traits\QueueTrait;
use Pbc\FormMail\Traits\SendTrait;
use Pbc\FormMail\Traits\MessageTrait;
use Pbc\FormMail\Helpers\FormMailHelper;
use App\Models\Championship\Player;
use Pbc\Premailer;

trait NotifyPlayerTrait
{
    use QueueTrait, SendTrait, MessageTrait;

    protected $premailer;
    protected $helper;

    public function notifyPlayerCreated(IndividualPlayerRequest $request, Player $player)
    {
        $premailer = new Premailer();
        $helper = new FormMailHelper();
        $fields = $request->all();
        $fields['fields'] = [];
        foreach(array_keys($fields) as $field) {
            if(in_array($field, ['_method','_token','fields'])) {
                continue;
            }
            array_push($fields['fields'], $field);
            $fields[$field.'-label'] = Strings::formatForTitle($field);
        }
        $request->replace($fields);
        $handler = new FormMailController($premailer, $helper);
        $data = [];
        $handler->requestHandler($request, $data);


    }
}

