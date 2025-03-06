<?php

namespace PartiMate\ThrottleMate;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Throwable;

class AdaptiveThrottler
{
    protected $fallback;
    protected $graceful;

    public function __construct($fallback = 'cache', $graceful = false)
    {
        $this->fallback = $fallback;
        $this->graceful = $graceful;
    }

    /**
     * @throws Throwable
     */
    public function throttle(callable $callback, string $key, int $limit, int $ttl = 60)
    {
        try {
            $attempts = Cache::get($key, 0);

            if ($attempts >= $limit) {
                if ($this->graceful) {
                    return $this->handleGracefulDegradation($callback);
                }
                throw new \Exception("Rate limit exceeded");
            }

            Cache::increment($key, 1);
            Cache::put($key, $attempts + 1, now()->addSeconds($ttl));

            return $callback();
        } catch (Throwable $e) {
            if ($this->graceful) {
                return $this->handleGracefulDegradation($callback);
            }
            throw $e;
        }
    }

    protected function handleGracefulDegradation(callable $callback)
    {
        if ($this->fallback === 'cache') {
            Cache::put(md5(json_encode($callback)), $callback(), now()->addMinutes(10));
        } elseif ($this->fallback === 'queue') {
            Queue::push(fn () => $callback());
        }
    }

    public static function make($fallback = 'cache', $graceful = false)
    {
        return new self($fallback, $graceful);
    }
}
