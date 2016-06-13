<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Championship\Game;

class GameRequest extends Request
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
                    'name' => 'required',
                    'uri' => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {

//                $id = $this->route()->game_id->id;
                $name = $this->route()->game_id->name;
//                dd("passed put patch");
                return [
//                    'id' => 'required|unique:mysql_champ.games,id,'.$id.',id',
                    'name' => 'required|unique:mysql_champ.games,name,'.$name.',name',
                    'uri' => 'required',
                ];
            }
            default:break;
        }
        return [
        ];

    }
    public function messages()
    {
        return [
            'name.required' => 'Your name is required.',
            'uri.required' => 'The URI is required.',
        ];
    }
}
