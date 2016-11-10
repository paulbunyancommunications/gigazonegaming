<?php
use Illuminate\Foundation\Http\FormRequest;
namespace App\Http\Requests;

class IndividualPlayerRequest extends FormRequest
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
                return [];
            case 'POST':
                return [
                    'username' => 'required|unique:mysql_champ.players,username',
                    'email' => 'required|email|unique:mysql_champ.players,email',
                ];
            case 'PUT':
            case 'PATCH':
                $name = $this->route()->player_id->username;
                $email = $this->route()->player_id->email;
                return [
                    'username' => 'required|unique:mysql_champ.players,username,' . $name . ',username',
                    'email' => 'required|unique:mysql_champ.players,email,' . $email . ',email',
                ];
            default:
                break;
        }
        return [];
    }
}
