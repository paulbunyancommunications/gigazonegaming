<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    public function csrfToken()
    {
        return csrf_token();
    }
}
