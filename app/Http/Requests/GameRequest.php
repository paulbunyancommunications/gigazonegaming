<?php

namespace App\Http\Requests;

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
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
            case 'POST':
                return [
                    'name' => 'required|unique:mysql_champ.games,name',
                    'title' => 'required|unique:mysql_champ.games,title',
                    'uri' => 'required|url',
                ];
            case 'PUT':
            case 'PATCH':
                return [
                    'name' => 'required|unique:mysql_champ.games,name,'.$this->route()->game_id->name.',name',
                    'title' => 'required|unique:mysql_champ.games,title,'.$this->route()->game_id->title.',title',
                    'uri' => 'required|url',
                ];
            default:
                break;
        }
        return [];
    }
    public function messages()
    {
        return [
            'name.required' => 'The game :attribute is required.',
            'name.unique' => 'The game :attribute is is already being used.',
            'title.required' => 'The game :attribute is required.',
            'title.unique' => 'The game :attribute is already being used.',
            'uri.required' => 'The game :attribute is required.',
            'uri.url' => 'The game :attribute must be a valid URL.',
        ];
    }
}
