<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Helpers\MemberHelper;

class CheckUserHasEdit
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
        $user = Auth::user();
        
        if (MemberHelper::getCurrentUserRole() != 'admin' && $user->id != $request->id)
        {
            if (MemberHelper::getCurrentUserRole() == 'boss')
            {
                return $next($request);
            }
            $errors[] = sprintf(trans('validation.not_direct_access'));
            return view('errors.system_error')->with('errors', $errors);
        }
        
        return $next($request);
    }
}
