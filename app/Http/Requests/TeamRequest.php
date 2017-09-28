<?php

namespace App\Http\Requests;

use App\Models\Championship\Team;
use App\Models\Championship\Tournament;

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
                $tournament_id = $this->tournament_id;
                if($tournament_id == null){
                    return [
                        'name' => 'required|uniqueWidth:mysql_champ.teams,self_,tournament_id',
                        'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id'
                    ];
                }
                return [
                    'name' => 'required|uniqueWidth:mysql_champ.teams,self_,tournament_id',
                    'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id'.$tournament_id.',tournament_id'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                $team_id = false;
                $requested_tournament_id = false;
                $original_tournament_id = false;
                $original_name = false;
                $requested_name = false;
                if(isset($this->name) and $this->name!=null) {
                    $requested_name = $this->name;
                }
                if(isset($this->tournament_id) and $this->tournament_id!=null) {
                    $requested_tournament_id = intval($this->tournament_id);
                }
                if($this->route()->team_id != null) {
                    if (isset($this->route()->team_id->name) and $this->route()->team_id->name != null) {
                        $original_name = $this->route()->team_id->name;
                    }
                    if (isset($this->route()->team_id->tournament_id) and $this->route()->team_id->tournament_id != null) {
                        $original_tournament_id = intval($this->route()->team_id->tournament_id);
                    }
                    if (isset($this->route()->team_id->id) and $this->route()->team_id->id != null) {
                        $team_id = $this->route()->team_id->id;
                    }
                }
//                dd($this->route(),$team_id, $requested_tournament_id, $original_tournament_id, $original_name, $requested_name);
                if($original_name == $requested_name and $original_tournament_id == $requested_tournament_id){ //same name, same tournament
                    return [
                        'name' => 'required',
                        'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id'.$requested_tournament_id.',tournament_id'
                    ];
                }else{//something change, name or tournament updated
                    $exits = Team::where([['name','=',$requested_name],['tournament_id', '=', $original_tournament_id],['id', '<>', $team_id] ])->exists();
                    $return = 'required|uniqueWidth:mysql_champ.teams,self_,tournament_id';
                    if(!$exits){$return='required|unique:mysql_champ.teams';}
                    return [
                        'name' => $return,
                        'tournament_id' => 'required|numeric:mysql_champ.tournament,tournament_id'.$original_tournament_id.',tournament_id'
                    ];
                }
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
            "name.unique_width" => 'A team with the exact same name already exists for this tournament, please select a different name.',
            'name.required' => 'The Team Name Field is required.',
            'name.unique' => 'The Team Name is in use, pick a new one.',
            'tournament_id.required' => 'The Tournament field can not be empty.',
            'tournament_id.numeric' => 'The Tournament field must be an tournament ID.',
        ];
    }
}
