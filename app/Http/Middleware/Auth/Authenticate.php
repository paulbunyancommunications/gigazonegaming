<?php

namespace App\Http\Middleware\Auth;

use Closure;

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
     * @return void
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
        $request->loggedIn = \Sentinel::getUser();
        if ($request->loggedIn && strpos($request->getRequestUri(), '/auth/') !== false && $request->getRequestUri() !== '/auth/logout') {
            return redirect('order');
        }

        if (!$request->loggedIn && strpos($request->getRequestUri(), '/auth/') === false) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }
        return $next($request);
    }
}
