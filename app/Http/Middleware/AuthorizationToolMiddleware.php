<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizationToolMiddleware
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
        $request->headers->set('Authorization', "Bearer ".$request->headers->get('X-Access-Token'));
    
        return $next($request);
    }
}
