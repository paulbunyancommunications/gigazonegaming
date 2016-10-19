<?php

namespace App\Http\Middleware;

use Closure;

class Api
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
        $okSites = [
          '209.191.200.242', //pbnet.pbndev.net
          '209.191.199.89', //paulbunyan.net
          '209.191.200.106', //gigazonegaming
          '192.168.56.1' //testing if you need another for testing, add a comma and add an extra string
        ];
        if (!in_array($_SERVER['REMOTE_ADDR'],$okSites)) {
            return redirect('/');
        }
        return $next($request);
    }
}
