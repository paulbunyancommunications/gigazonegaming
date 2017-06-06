<?php

namespace App\Http\Requests;

use App\Models\Championship\Tournament;
use Illuminate\Http\Request as BaseRequest;
use Pbc\Bandolier\Type\Numbers;

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
        if(Tournament::where( 'name', '=', $this->tournament )->exists()) {
            $tournament_id = Tournament::where('name', '=', $this->tournament)->first()->id;
            $rules = [
                'email' => 'required|email|unique:mysql_champ.players,email',
                'name' => 'required',
                'team-captain-lol-summoner-name' => 'required|unique:mysql_champ.players,username',
                'team-captain-phone' => 'required',
                'tournament' => 'required|exists:mysql_champ.tournaments,name',
                'team-name' => 'required|uniqueWidth:mysql_champ.teams,=name,tournament_id>'.$tournament_id,
            ];
        }else{
            $rules = [
                'email' => 'required|email|unique:mysql_champ.players,email',
                'name' => 'required',
                'team-captain-lol-summoner-name' => 'required|unique:mysql_champ.players,username',
                'team-captain-phone' => 'required',
                'tournament' => 'required|exists:mysql_champ.tournaments,name',
                'team-name' => 'required|unique:mysql_champ.teams',
            ];
        }
        for ($i = 1; $i <= 2; $i++) {
            if ($this->request->get('teammate-'.Numbers::toWord($i).'-lol-summoner-id')) {
                $rules['teammate-' . Numbers::toWord($i) . '-lol-summoner-id'] = 'exists:mysql_champ.player,id';
            } else {
                $rules['teammate-' . Numbers::toWord($i) . '-lol-summoner-name'] = 'required|unique:mysql_champ.players,username';
                $rules['teammate-' . Numbers::toWord($i) . '-email-address'] = 'required|email|unique:mysql_champ.players,email';
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
            "name.unique_width" => 'A team with the exact same name already exist for this tournament, please select a different name.',
            'email.required' => 'The team captain email address is required.',
            'email.unique' => 'The team captain email address is already assigned to a different user.',
            'email.email' => 'The team captain email address myst be a valid email address (someone@somewhere.com for example).',
            'name.required' => 'The name of the team captain is required.',
            'team-captain-lol-summoner-name.required' => 'The team captain LOL summoner name is required.',
            'team-captain-lol-summoner-name.unique' => 'The team captain LOL summoner name is already assigned to a different user.',
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
