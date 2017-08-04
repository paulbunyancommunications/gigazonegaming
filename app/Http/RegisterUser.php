<?php
namespace App\Http;
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 7/18/17
 * Time: 10:29 AM
 */
use App\Http\Requests\Auth\PlayerRegisterRequest;
use App\PlayerUpdate;

class RegisterUser
{
    protected $request;

    public function __construct(PlayerRegisterRequest $request)
    {
        $this->request = $request;
    }

    public function register()
    {
        $this->validateRequest($this->request);
        $message = $this->createUser($this->request);
        return view('/LeagueOfLegends/register')->withMessage($message);

    }

    protected function validateRequest($request){

        return $request;
    }
    protected function createUser($request){

        return PlayerUpdate::generateUser($request);
    }

}