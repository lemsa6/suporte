<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | application.
    |
    */

    'blacklisted_ips' => [
        // Add IP addresses that should be blocked
    ],

    'rate_limiting' => [
        'login' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'api' => [
            'max_attempts' => 60,
            'decay_minutes' => 1,
        ],
        'general' => [
            'max_attempts' => 100,
            'decay_minutes' => 1,
        ],
    ],

    'password' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'max_age_days' => 90,
    ],

    'file_upload' => [
        'max_size_kb' => 25000,
        'allowed_types' => [
            'pdf', 'jpg', 'jpeg', 'png', 'zip', 'txt', 'log',
            'doc', 'docx', 'xls', 'xlsx'
        ],
        'scan_for_malware' => true,
    ],

    'session' => [
        'lifetime' => 120, // minutes
        'secure' => env('SESSION_SECURE', false),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    'cors' => [
        'allowed_origins' => [
            env('APP_URL', 'http://localhost'),
        ],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-CSRF-TOKEN'],
        'max_age' => 86400,
    ],

    'headers' => [
        'x_frame_options' => 'DENY',
        'x_content_type_options' => 'nosniff',
        'x_xss_protection' => '1; mode=block',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'content_security_policy' => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;",
    ],

    'encryption' => [
        'key' => env('APP_KEY'),
        'cipher' => 'AES-256-CBC',
    ],

    'audit' => [
        'enabled' => true,
        'log_failed_logins' => true,
        'log_password_changes' => true,
        'log_sensitive_operations' => true,
        'retention_days' => 365,
    ],

    'backup' => [
        'enabled' => env('BACKUP_ENABLED', false),
        'frequency' => 'daily',
        'retention_days' => 30,
        'encrypt' => true,
    ],
];
