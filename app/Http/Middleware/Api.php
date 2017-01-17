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
          '127.0.0.1', //localhost
          '209.191.200.242', //pbnet.pbndev.net
          '209.191.199.89', //paulbunyan.net
          '209.191.200.106', //gigazonegaming
          '192.168.56.1', //testing if you need another for testing, add a comma and add an extra string
          'gigazonegaming.com',
          'gigazonegaming.local',
        ];
        //https://laracasts.com/discuss/channels/general-discussion/laravel-5-referrer-url/replies/105536
        $hostName = parse_url(app('Illuminate\Routing\UrlGenerator')->previous(), PHP_URL_HOST);

        if (!in_array($hostName, $okSites) && !in_array($request::ip(), $okSites)) {
            return \Response::json(['error' => ['Not allowed!']], 400);
        }
        return $next($request);
    }
}
