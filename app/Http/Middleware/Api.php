<?php

namespace App\Http\Middleware;

use Closure;

class Api
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
        $token = $request->header('neac');
        $request->headers->set('Content-Type', 'application/json');
        if($token != 'Token M24dN00RacN77TaNLTM16e27TKNa84bb36KR13M3aL9b21M34KcM8OaRK7aKOM58') {
            return response()->json(['message' => 'Token mismatch!'], 401);
        }
        return $next($request);
    }
}
