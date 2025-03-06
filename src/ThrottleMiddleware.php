<?php

namespace PartiMate\ThrottleMate;

use Closure;

class ThrottleMiddleware
{
    public function handle($request, Closure $next)
    {
        return app(AdaptiveThrottler::class)->attempt(fn() => $next($request));
    }
}