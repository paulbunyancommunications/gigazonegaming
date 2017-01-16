<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SentinelRedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        if (Sentinel::check()) {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}
