<?php

namespace App\Http\Requests\Auth\Password;

use App\Http\Requests\Request;

class PasswordResetPasswordRequest extends Request
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
            'password' => 'required|min:6',
            'password_repeat' => 'required|same:password',
            'code' => 'required'
        ];
    }
}
