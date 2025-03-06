<?php

namespace PartiMate\ThrottleMate;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdaptiveThrottler
{
    protected int $retryLimit;
    protected bool $fallbackEnabled;

    public function __construct()
    {
        $this->retryLimit = config('throttlemate.retry_limit');
        $this->fallbackEnabled = config('throttlemate.fallback_enabled');
    }

    public function attempt(callable $callback)
    {
        $attempt = 0;

        do {
            try {
                return $callback();
            } catch (\Exception $e) {
                $attempt++;
                Log::warning("ThrottleMate attempt {$attempt} failed.");

                if ($attempt >= $this->retryLimit) {
                    if ($this->fallbackEnabled) {
                        Log::info("Fallback triggered!");
                        return $this->fallback();
                    }
                    throw $e;
                }

                usleep(100000 * $attempt); // Exponential Backoff
            }
        } while ($attempt < $this->retryLimit);
    }

    protected function fallback()
    {
        return Cache::remember('throttle_response', now()->addMinutes(5), fn() => 'Fallback Response');
    }
}