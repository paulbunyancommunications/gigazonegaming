<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class LolIndividualSignUpRequest extends Request
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
            'your-lol-summoner-name' => 'required',
            'email' => 'required|email',
            'your-phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Your name is required.',
            'your-lol-summoner-name.required' => 'Your League of Legends summoner name is required.',
            'email.required' => 'Your email address is required.',
            'email.email' => 'Your email address must be a valid address (someone@somewhere.com for example).',
            'your-phone.required' => 'Your phone number is required.',
        ];
    }
}
