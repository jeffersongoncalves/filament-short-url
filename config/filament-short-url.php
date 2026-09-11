<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Metrics Page
    |--------------------------------------------------------------------------
    |
    | - slug: route slug for the global MetricsPage. Defaults to
    |   "short-url-metrics" instead of Filament's usual kebab-case-of-class-
    |   name default ("metrics-page") to avoid colliding with another
    |   jeffersongoncalves/* plugin's own MetricsPage in a host panel that
    |   installs both. Override here if it still collides with something
    |   else, or to match your own URL scheme.
    |
    */
    'metrics_page' => [
        'slug' => env('FILAMENT_SHORT_URL_METRICS_PAGE_SLUG', 'short-url-metrics'),
    ],

];
