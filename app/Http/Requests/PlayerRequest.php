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
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @todo Nelson, please fix switch statement, only use colons and breaks between conditions http://php.net/manual/en/control-structures.switch.php
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'username' => 'required|unique:mysql_champ.players,username',
                    'team_id' => 'required:mysql_champ.players,team_id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
//                dd($this->route());
//                $id = $this->route()->team_id->player_id;
                $t_id = $this->route()->player_id->team_id;
                $name = $this->route()->player_id->username;
//                dd("passed put patch");
                return [
                    'username' => 'required|unique:mysql_champ.players,username,'.$name.',username',
                    'team_id' => 'required:mysql_champ.players,team_id,'.$t_id.',team_id',
                ];
            }
            default:break;
        }
        return [
        ];

    }

    /**
     * @todo Nelson, Where are the rules for required name and game_id? Are they handled in the model? They should be in the rules method.
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Your Name is required.',
            'game_id.required' => 'The Game ID is required.',
        ];
    }
}
