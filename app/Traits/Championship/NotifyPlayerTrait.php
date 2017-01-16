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

use App\Http\Requests\PlayerRequest;
use Carbon\Carbon;
use Pbc\Bandolier\Type\Strings;
use Pbc\FormMail\Http\Controllers\FormMailController;
use Pbc\FormMail\Helpers\FormMailHelper;
use App\Models\Championship\Player;
use Pbc\Premailer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

trait NotifyPlayerTrait
{

    protected $premailer;
    protected $helper;

    /**
     * @param PlayerRequest $request
     * @param Player $player
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifyPlayerCreated(PlayerRequest $request, Player $player)
    {
        $premailer = new Premailer();
        $helper = new FormMailHelper();
        $handler = new FormMailController($premailer, $helper);

        $fields = $request->all();
        $fields['fields'] = [];
        foreach(array_keys($fields) as $field) {
            if(in_array($field, ['_method','_token','fields','submit'])) {
                continue;
            }
            array_push($fields['fields'], $field);
            $fields[$field.'-label'] = Strings::formatForTitle($field);
        }
        $request->replace($fields);

        $hostUrl = parse_url(Config::get('app.url'), PHP_URL_HOST);
        $paramsForLang = array_merge(
            $player->toArray(),
            [
                'url' => $hostUrl,
                'form' => '',
                'time' => Carbon::now(),
            ]
        );
        $data = [
            'subject' => json_encode([
                $handler::RECIPIENT => Lang::get($helper::resourceRoot() .'.'. Route::currentRouteName() . '.subject.'.$handler::RECIPIENT, $paramsForLang),
                $handler::SENDER => Lang::get($helper::resourceRoot() .'.'. Route::currentRouteName() . '.subject.'.$handler::SENDER, $paramsForLang),
            ]),
            $handler::SENDER => 'players@'.$hostUrl,
            $handler::RECIPIENT => $request->input('email'),
            'head' => json_encode([
                $handler::RECIPIENT => Lang::get($helper::resourceRoot() .'.'. Route::currentRouteName() . '.'.$handler::RECIPIENT, $paramsForLang),
                $handler::SENDER => Lang::get($helper::resourceRoot() .'.'. Route::currentRouteName() . '.'.$handler::SENDER, $paramsForLang),
            ]),
        ];
        return $handler->requestHandler($request, $data);
    }
}

