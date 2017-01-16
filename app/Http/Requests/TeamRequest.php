<?php

namespace App\Http\Requests;

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
                    'name' => 'required|unique:mysql_champ.teams,name',
                    'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                $name = $this->route()->team_id->name;
                $tournament_id = $this->route()->team_id->tournament_id;
                return [
                    'name' => 'required|unique:mysql_champ.teams,name,'.$name.',name',
                    'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id'.$tournament_id.',tournament_id',
                ];
            }
            default:break;
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
            'name.required' => 'The Team Name Field is required.',
            'name.unique' => 'The Team Name is in use, pick a new one.',
            'tournament_id.required' => 'The Tournament field can not be empty.',
            'tournament_id.numeric' => 'The Tournament field must be an tournament ID.',
        ];
    }
}
