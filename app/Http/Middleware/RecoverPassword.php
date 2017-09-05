<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 8/14/17
 * Time: 10:48 AM
 */

namespace App\Http\Middleware;


use App\Http\Requests\RecoverPasswordRequest;

class RecoverPassword
{
    protected $request;

    public function __construct(RecoverPasswordRequest $request)
    {
        $this->request = $request;
    }

    public function recovery()
    {
        $this->validateRequest($this->request);
        return $this->newPassword($this->request);
    }

    protected function validateRequest($request){

        return $request;
    }

    protected function newPassword($request){

        return PlayerUpdate::createNewPassword($request);
    }

}