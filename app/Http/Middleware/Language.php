<?php

namespace App\Http\Middleware;

use App;
use Closure;

class Language
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
        if ($request->cookie('language')) App::setLocale($request->cookie('language'));

        return $next($request);
    }
}
