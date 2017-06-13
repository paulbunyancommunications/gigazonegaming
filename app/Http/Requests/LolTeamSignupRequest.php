<?php

namespace App\Http\Requests;

use App\Models\Championship\Player;
use App\Models\Championship\Tournament;
use Illuminate\Http\Request as BaseRequest;
use Pbc\Bandolier\Type\Numbers;
use Psy\Util\Json;

/**
 * Class LolTeamSignUpRequest
 *
 * Used for creating a new LoL team from the front end site
 *
 * @package App\Http\Requests
 */
class LolTeamSignUpRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(isset($_REQUEST['tournament']) AND $_REQUEST['tournament']!='' AND Tournament::where('name', '=', $_REQUEST['tournament'])->exists()) {
            $tournament_id = Tournament::where('name', '=', $_REQUEST['tournament'])->first()->id;
            $rules = [
                'email' => 'required|email',
                'name' => 'required',
                'team-captain-lol-summoner-name' => 'required',
                'team-captain-phone' => 'required',
                'tournament' => 'required|exists:mysql_champ.tournaments,name',
                'team-name' => 'required|uniqueWidth:mysql_champ.teams,=name,tournament_id>'.$tournament_id,
            ];
        }else{
            $rules = [
                'email' => 'required|email',
                'name' => 'required',
                'team-captain-lol-summoner-name' => 'required',
                'team-captain-phone' => 'required',
                'tournament' => 'required|exists:mysql_champ.tournaments,name',
                'team-name' => 'required|unique:mysql_champ.teams,name',
            ];
        }

        for ($i = 1; $i <= 3; $i++) {
            if (isset($_REQUEST['teammate-'.Numbers::toWord($i).'-lol-summoner-id']) AND Player::where("email", '=', $_REQUEST['teammate-'.Numbers::toWord($i).'-email-address'])->exists()) {
                $id = Player::where("email", '=', $_REQUEST['teammate-'.Numbers::toWord($i).'-email-address'])->first()->id;
                $_REQUEST['teammate-'.Numbers::toWord($i).'-lol-summoner-id'] = $id;
            } elseif (isset($_REQUEST['teammate-'.Numbers::toWord($i).'-lol-summoner-id'])) {
                $rules['teammate-' . Numbers::toWord($i) . '-lol-summoner-id'] = 'exists:mysql_champ.player,id';
            } else {
                $rules['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'] = 'required';
                $rules['teammate-' . Numbers::toWord($i) . '-email-address'] = 'required|email';
            }
            if($i<3) {
                if (isset($_REQUEST['alternate-' . Numbers::toWord($i) . '-summoner-id'])) {
                    $rules['alternate-' . Numbers::toWord($i) . '-summoner-id'] = 'exists:mysql_champ.player,id';
                } elseif(isset($rules['alternate-' . Numbers::toWord($i) . '-email-address'])) {
                    $rules['alternate-' . Numbers::toWord($i) . '-summoner-name'] = 'required';
                    $rules['alternate-' . Numbers::toWord($i) . '-email-address'] = 'required|email';
                }
            }
        }

        return $rules;
    }

    /**
     * Setup rules messages
     * @return array
     */
    public function messages()
    {
        $messages = [
            "team-name.uniqueWidth" => 'A team with the exact same name already exists for this tournament, please select a different name.',
            "team-name.unique_width" => 'A team with the exact same name already exists for this tournament, please select a different name.',
            'email.required' => 'The team captain email address is required.',
            'email.email' => 'The team captain email address myst be a valid email address (someone@somewhere.com for example).',
            'name.required' => 'The name of the team captain is required.',
            'team-captain-lol-summoner-name.required' => 'The team captain LOL summoner name is required.',
            'team-captain-phone.required' => 'The team captain phone number is required.',
            'team-name.required' => 'The team name is required.',
        ];
        
        for ($i = 1; $i <= 2; $i++) {
            $messages['teammate-'.Numbers::toWord($i).'-lol-summoner-id.exists'] = 'The summoner selected for teammate '.Numbers::toWord($i). ' was not found.';
            $messages['teammate-'.Numbers::toWord($i).'-lol-summoner-name.required'] = 'The summoner name for team member '.Numbers::toWord($i).' is required.';
            $messages['teammate-'.Numbers::toWord($i).'-email-address.required'] = 'The email address for team member '.Numbers::toWord($i).' is required.';
            $messages['teammate-'.Numbers::toWord($i).'-email-address.unique'] = 'The email address for team member '.Numbers::toWord($i).' is already in use by another player.';
            $messages['teammate-'.Numbers::toWord($i).'-email-address.email'] = 'The email address for team member '.Numbers::toWord($i).' must be a valid email address (someone@somewhere.com for example).';
        }

        return $messages;
    }
}
