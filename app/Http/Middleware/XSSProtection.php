<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
{
    /**
     * Use XSS protection for method POST and PUT and not process with JSON input
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            if (is_string($input)) {
                $input = strip_tags($input);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
