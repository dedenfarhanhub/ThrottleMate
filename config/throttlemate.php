<?php

return [
    'retry_limit' => env('THROTTLEMATE_RETRY_LIMIT', 3),
    'fallback_enabled' => env('THROTTLEMATE_FALLBACK', false),
    'fallback_driver' => 'cache', // cache | queue
];
