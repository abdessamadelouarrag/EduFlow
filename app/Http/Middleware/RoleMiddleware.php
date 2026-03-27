<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthenticated.');
        }

        $roles = array_slice(func_get_args(), 2);

        if ($roles !== [] && ! in_array($request->user()->role, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'You are not allowed to access this resource.');
        }

        return $next($request);
    }
}
