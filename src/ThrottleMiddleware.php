<?php

namespace PartiMate\ThrottleMate;

use Closure;

class ThrottleMiddleware
{
    protected $limit;
    protected $ttl;

    public function __construct()
    {
        $this->limit = config('throttlemate.limit', 100); // Default Limit
        $this->ttl = config('throttlemate.ttl', 60); // Default TTL
    }

    public function handle($request, Closure $next)
    {
        $key = 'throttle:' . $request->ip();

        return app(AdaptiveThrottler::class)->throttle(
            fn () => $next($request),
            $key,
            $this->limit,
            $this->ttl
        );
    }
}
