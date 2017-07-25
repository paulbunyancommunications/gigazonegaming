<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 7/21/17
 * Time: 9:47 AM
 */

namespace App\Models\Auth;

use App\Http\Requests\UpdatePlayerRequest;

class UpdatePlayerInfo
{
    protected $request;

    public function __construct(UpdatePlayerRequest $request)
    {
        $this->request = $request;
    }

    public function update()
    {
        $this->validateRequest($this->request);
        $info = PlayerUpdate::updateInfo($this->request);
        return $info;
    }

    protected function validateRequest($request){

        return $request;
    }
}