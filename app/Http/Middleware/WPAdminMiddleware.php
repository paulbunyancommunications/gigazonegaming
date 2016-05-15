<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class WPAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        require_once dirname(__DIR__) .'/../../public_html/wp/wp-load.php';

        if (is_user_logged_in() and (is_super_admin() or is_user_admin())) {
            return $next($request);
        } else {
            return Redirect::to(
                env('APP_URL', 'http://localhost') . '/wp/wp-login.php?redirect_to=' . urlencode($request->getRequestUri()) . '&reauth=1'
            );
        }
    }
}
