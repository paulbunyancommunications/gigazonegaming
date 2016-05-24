<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Models\Championship\Tournament;

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
     *
     * @return array
     */
    public function rules()
    {
//        protected $fillable = ['name', 'game_id','updated_by','updated_on'];
//        dd($this->route());
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
                    //'game_id' => 'required|exists:mysql_champ.games:id', TODO
                    'name' => 'required|unique:mysql_champ.tournaments',
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
                    //'game_id' => 'required|exists:mysql_champ.games:id', TODO
                    'name' => 'required|unique:mysql_champ.tournaments,name,'.$name.',name',
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
