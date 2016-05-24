<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Pbc\Bandolier\Type\Numbers;

/**
 * Class LolTeamSignUpRequest
 * @package App\Http\Requests
 */
class LolTeamSignUpRequest extends Request
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
        $rules = [
            'email' => 'required|email',
            'name' => 'required',
            'team-captain-lol-summoner-name' => 'required',
            'team-captain-phone' => 'required',
            'tournament' => 'required|exists:mysql_champ.tournaments,name',
        ];
        for ($i = 1; $i <= 2; $i++) {
            $rules['teammate-'.Numbers::toWord($i).'-lol-summoner-name'] = 'required';
            $rules['teammate-'.Numbers::toWord($i).'-email-address'] = 'required|email';
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
            'email.required' => 'The team captain email address is required.',
            'email.email' => 'The team captain email address myst be a valid email address (someone@somewhere.com for example).',
            'name.required' => 'The name of the team captain is required.',
            'team-captain-lol-summoner-name.required' => 'The team captain LOL summoner name is required.',
            'team-captain-phone.required' => 'The team captain phone number is required.',
        ];
        
        for ($i = 1; $i <= 4; $i++) {
            $messages['teammate-'. Numbers::toWord($i).'-lol-summoner-name.required'] = 'The summoner name for team member '.Numbers::toWord($i).' is required.';
            $messages['teammate-'.Numbers::toWord($i).'-email-address.required'] = 'The email address for team member '.Numbers::toWord($i).' is required.';
            $messages['teammate-'.Numbers::toWord($i).'-email-address.email'] = 'The email address for team member '.Numbers::toWord($i).' must be a valid email address (someone@somewhere.com for example).';
        }

        return $messages;
    }
}
