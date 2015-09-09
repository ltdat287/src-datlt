<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\MemberHelper;

class CheckDirectAccess
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
        // Check permission delete current user.
        if (MemberHelper::checkLogin()->id == $request->id) {
            $errors[] = sprintf(trans('validation.not_direct_access'));

            return view('errors.system_error')->with('errors', $errors);
        }

        return $next($request);
    }
}
