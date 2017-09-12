<?php

namespace App\Http\Requests;

/**
 * Class TournamentRequest
 * @package App\Http\Requests
 */
class TournamentRequest extends Request
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
                    'game_id' => 'required|numeric|exists:mysql_champ.games,id',
                    'name' => 'required|unique:mysql_champ.tournaments',
                    'max_players' => 'required|numeric',
                    'max_teams' => 'required|numeric',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
//                dd($this->route());
                $id = $this->route()->tournament_id->id;
                $name = $this->route()->tournament_id->name;
//                dd("passed put patch");
                return [
                    'game_id' => 'required|numeric|exists:mysql_champ.games,id',
                    'name' => 'required|unique:mysql_champ.tournaments,name,'.$name.',name',
                    'max_players' => 'required|numeric',
                    'max_teams' => 'required|numeric',
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
            'name.required' => 'The Tournament Name is required.',
            'game_id.required' => 'A Game must be selected.',
            'game_id.numeric' => 'A Game must be selected from the list.',
            'max_players.numeric' => 'The NUMBER of players needs to be ... a number, LOL.',
            'max_players.required' => 'The number of players is a required field.',
            'max_teams.numeric' => 'The NUMBER of players needs to be ... a number, LOL.',
            'max_teams.required' => 'The number of players is a required field.',
        ];
    }
}
