<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests;

class SessionController extends Controller
{
    public function csrfToken()
    {
        return csrf_token();
    }
}
