<?php
// config/business-registration.php

return [
    /*
    |--------------------------------------------------------------------------
    | Business Registration Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the business registration
    | system including default values, file paths, and feature toggles.
    |
    */

    'defaults' => [
        'status' => 'DRAFT',
        'user_status' => 'ACTIVE',
        'fiscal_year_start' => '01-01',
        'fiscal_year_end' => '12-31',
    ],

    'file_uploads' => [
        'registration_documents' => [
            'disk' => env('BUSINESS_REGISTRATION_DISK', 'public'),
            'path' => 'business-registrations/{business_id}/{type}',
            'max_size' => 10240, // 10MB in KB
            'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        ],
    ],

    'features' => [
        'auto_generate_account_codes' => true,
        'require_all_registrations' => false,
        'allow_multiple_businesses_per_user' => true,
        'track_history' => true,
        'enable_business_switching' => true,
    ],

    'registration_steps' => [
        'basic_info' => [
            'required' => true,
            'order' => 1,
        ],
        'address' => [
            'required' => true,
            'order' => 2,
        ],
        'tax_info' => [
            'required' => true,
            'order' => 3,
        ],
        'registrations' => [
            'required' => false,
            'order' => 4,
        ],
        'fiscal_year' => [
            'required' => true,
            'order' => 5,
        ],
        'chart_of_accounts' => [
            'required' => true,
            'order' => 6,
        ],
    ],

    'notifications' => [
        'registration_expiry_days' => 30,
        'send_welcome_email' => true,
        'notify_on_user_added' => true,
    ],
];
