<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class SentinelStandardUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Sentinel::getUser();
        $users = Sentinel::findRoleByName('Users');

        if (!$user->inRole($users)) {
            return redirect('/');
        }
        return $next($request);
    }
}
