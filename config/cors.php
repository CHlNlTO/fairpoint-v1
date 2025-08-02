<?php
// config/cors.php
// Configure CORS in Laravel to allow Next.js requests

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:3001',
        'https://fairpointca.com',
        'https://fairpointca.vercel.app',
    ],

    'allowed_origins_patterns' => [
        // Allow all subdomains in development
        // '/^https?:\/\/.*\.localhost(:\d+)?$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
