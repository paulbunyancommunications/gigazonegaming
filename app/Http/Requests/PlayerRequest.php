<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\Championship\Player;

class PlayerRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (is_user_logged_in() and (is_super_admin() or is_user_admin())) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'username' => 'required|unique:mysql_champ.players,username',
                    'email' => 'required|email|unique:mysql_champ.players,email',
                    'phone' => 'phone:US'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                $email = $this->route()->player_id->email;
                $name = $this->route()->player_id->username;
                return [
                    'email' => 'required|email|unique:mysql_champ.players,email,'.$email.',email',
                    'username' => 'required|unique:mysql_champ.players,username,'.$name.',username',
                    'phone' => 'phone:US'
                ];
            }
            default:
                break;
        }
        return [
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'username.required' => 'A Username is required.',
            'username.unique' => 'The Username is already in use, please select a new one.',
            'email.required' => 'A email address is required.',
            'email.unique' => 'A email address is already been used, use your previously created account or create a new one. ',
            'email.email' => "That doesn't look like an email, try again.",
            'phone.phone' => "The phone number isn't a valid one, or you forgot the area code.",
        ];
    }
}
