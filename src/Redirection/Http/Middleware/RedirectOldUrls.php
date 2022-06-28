<?php

namespace Webup\LaravelHelium\Redirection\Http\Middleware;

use Closure;
use Webup\LaravelHelium\Redirection\Entities\Redirection;

class RedirectOldUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $redirection = Redirection::where("from", str_replace(config("app.url"), "", url()->current()))->first();
        if ($redirection) {
            return redirect($redirection->to, 301);
        }

        return $next($request);
    }
}
