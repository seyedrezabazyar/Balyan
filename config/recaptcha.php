<?php

return [
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'version' => 'v3',
    'min_score' => 0.3,
];
