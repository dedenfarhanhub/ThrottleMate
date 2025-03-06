<?php

return [
    'enabled' => env('THROTTLEMATE_ENABLED', true),

    'max_attempts' => env('THROTTLEMATE_MAX_ATTEMPTS', 100),

    'decay_seconds' => env('THROTTLEMATE_DECAY_SECONDS', 60),

    // Optional Graceful Degradation
    'graceful_degradation' => env('THROTTLEMATE_GRACEFUL_DEGRADATION', false),

    // Fallback Type: cache or queue
    'fallback' => env('THROTTLEMATE_FALLBACK', 'cache'),
];
