<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $loggedIn = Sentinel::getUser();
        if($loggedIn && strpos($request->getRequestUri(), '/auth/') !== false && $request->getRequestUri() !== '/auth/logout') {
            return redirect('order');
        }

        if (!$loggedIn && strpos($request->getRequestUri(), '/auth/') === false) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
        return $next($request);
    }
}
