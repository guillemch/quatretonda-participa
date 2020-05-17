<?php

namespace App\Http\Middleware;

use Closure;
use App\Edition;

class VoteOpen
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
        $edition = Edition::current();

        if (!$edition->count()) {
            return abort(503, 'You must first create an edition');
        }

        if (!$edition->isOpen()) {
            return redirect('/');
        }

        $request->attributes->add(['edition_id' => $edition->id]);

        return $next($request);
    }
}
