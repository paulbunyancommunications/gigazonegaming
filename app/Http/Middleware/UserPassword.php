<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 7/21/17
 * Time: 9:47 AM
 */

namespace App\Http\Middleware;

use App\Http\Requests\UserPasswordRequest;

class UserPassword
{
    protected $request;

    public function __construct(UserPasswordRequest $request)
    {
        $this->request = $request;
    }

    public function createPassword()
    {
        $this->validateRequest($this->request);
        $info = PlayerUpdate::createPassword($this->request);
        return $info;
    }

    protected function validateRequest($request){

        return $request;
    }
}