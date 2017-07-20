<?php

namespace App\Http\Requests\Auth;
use App\Http\Requests\Request;

class PlayerRegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required',
            'email' => 'required|email',
            'username' =>'required',
            'password' => 'required|confirmed',
        ];
    }
}
