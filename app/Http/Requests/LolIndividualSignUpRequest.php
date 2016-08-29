<?php

namespace App\Http\Requests;

use Illuminate\Http\Request as BaseRequest;

class LolIndividualSignUpRequest extends BaseRequest
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
        return [
            'name' => 'required',
            'your-lol-summoner-name' => 'required|unique:mysql_champ.individual_players,username|unique:mysql_champ.players,username',
            'email' => 'required|email',
            'your-phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Your name is required.',
            'your-lol-summoner-name.required' => 'Your League of Legends summoner name is required.',
            'your-lol-summoner-name.unique' => 'Your League of Legends summoner name is already being used by someone else.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Your email address must be a valid address (someone@somewhere.com for example).',
            'your-phone.required' => 'Your phone number is required.',
        ];
    }
}
