<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckUserDisabled
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
        // Check user disabled in db.
        if ($request->id) {
            $user = User::find($request->id);
            if ( empty($user) || $user->disabled ) {
                $errors[] = sprintf(trans('validation.deleted_id'), $request->id);
                return view('errors.system_error')->with('errors', $errors);
            }
        } else {
            return redirect('/');
        }
        
        return $next($request);
    }
}
