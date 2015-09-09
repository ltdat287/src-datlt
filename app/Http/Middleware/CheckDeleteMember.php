<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckDeleteMember
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
        // Get user.
        if ($request->id)
        {
            $user = User::find($request->id);

            $errors = array();

            if (! $user)
            {
                $errors[] = sprintf(trans('validation.user_not_exists'), $request->id);
                return view('errors.system_error')->with('errors', $errors);
            }

            // Check user has deleted.
            if ($user->disabled == true)
            {
                $errors[] = sprintf(trans('validation.deleted_id'), $request->id);
                return view('errors.system_error')->with('errors', $errors);
            }

            // Check user want delete has child employ.
            if (count(User::where('boss_id', '=', $request->id)->get()))
            {
                $errors[] = trans('validation.exists_employ_child');
                return view('errors.system_error')->with('errors', $errors);
            }
        }

        return $next($request);
    }
}
