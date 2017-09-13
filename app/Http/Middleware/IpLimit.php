<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Limit;

class IpLimit
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
        $maxVotes = config('participa.max_per_ip');
        $maxFailedLookUps = config('participa.max_failed_lookups');
        $inPerson = Auth::user();

        if(Limit::exceeded($request, 'IDFailedLookUp', $maxFailedLookUps)) {
            return response()->json([
                'IpLimit' => [__('participa.lookup_limit_exceeded')]
            ], 422);
        }

        if(!$inPerson && Limit::exceeded($request, 'Vote', $maxVotes)) {
            return response()->json([
                'IpLimit' => [__('participa.ip_limit_exceeded')]
            ], 422);
        }

        return $next($request);
    }
}
