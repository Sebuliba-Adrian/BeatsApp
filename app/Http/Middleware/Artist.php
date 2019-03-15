<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class Artist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->is_artist) {
            return response()->json(["success"=> false, "message"=>"Sorry you are not authorised to perform this operation"], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
