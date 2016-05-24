<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\Championship\Team;

class TeamRequest extends Request
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
     *
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
                    'name' => 'required|unique:mysql_champ.teams,name',
                    'tournament_id' => 'required:mysql_champ.teams,tournament_id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
//                dd($this->route());
//                $id = $this->route()->tournament_id->team_id;
                $t_id = $this->route()->team_id->tournament_id;
                $name = $this->route()->team_id->name;
//                dd("passed put patch");
                return [
                    'name' => 'required|unique:mysql_champ.teams,name,'.$name.',name',
                    'tournament_id' => 'required:mysql_champ.teams,tournament_id,'.$t_id.',tournament_id',
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
            'name.required' => 'Your Name is required.',
            'game_id.required' => 'The Game ID is required.',
        ];
    }
}
