<?php

namespace App\Http\Requests;

class ScoreRequest extends Request
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
                    'player' => 'required|exists:mysql_champ.players,id',
                    'tournament' => 'required|exists:mysql_champ.tournaments,id',
                    'score' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'score' => 'required'
                ];
            }
            default:break;
        }
        return [
        ];

    }
}
