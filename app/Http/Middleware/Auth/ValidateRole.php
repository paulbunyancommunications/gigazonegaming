<?php

namespace App\Http\Middleware\Auth;

use Closure;

class ValidateRole
{

    protected $lockedRoles = ['admin', 'manager', 'user'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        switch($request->getMethod()) {
            case('POST'):
                $getRole = \Sentinel::getRoleRepository()->findBySlug($request->input('slug'));
                if($getRole && in_array($request->input('slug'), $this->lockedRoles)) {
                    return \Redirect::back()->withInput()->with('error', 'Can not '.strtolower($request->getMethod()).' role ' . $getRole->name);
                }
                break;
            case('GET'):
                $parts = explode('/', $request->getPathInfo());
                $getRole = isset($parts[2]) ? \Sentinel::getRoleRepository()->findById($parts[2]) : null;
                if(isset($parts[3])
                    && $parts[3] === 'edit'
                    && $getRole
                    && in_array($getRole->getRoleSlug(), $this->lockedRoles))
                {
                    return \Redirect::back()->withInput()->with('error', 'Can not '.$parts[3].' role ' . $getRole->name);
                }
                break;
            case('DELETE'):
            case('PUT'):
            case('PATCH'):
                $parts = explode('/', $request->getPathInfo());
                $getRole = \Sentinel::getRoleRepository()->findById($parts[2]);
                if($getRole && in_array($getRole->getRoleSlug(), $this->lockedRoles)) {
                    return \Redirect::back()->withInput()->with('error', 'Can not '.strtolower($request->getMethod()).' role ' . $getRole->name);
                }
                break;
        }

        return $next($request);
    }
}
