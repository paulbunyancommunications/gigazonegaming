<?php

namespace App\Http\Middleware\Auth;

use Sentinel;
use Closure;

class SentinelHasAccess
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
        if (!$user->hasAccess([$this->parseRouteName($request)])) {
            return redirect()->back()->with('error', 'You don\'t have permission to this route.');
        }
        return $next($request);
    }

    /**
     * Parse Route name
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private function parseRouteName($request)
    {
        $name = $request->route()->getName();
        if (strpos($name, '.') !== false) {
            $nameArray = explode('.', $name);
        } else {
            $nameArray = (array)$name;
        }
        $nameArray = array_filter($nameArray);
        return implode('.', $nameArray);
    }
}
