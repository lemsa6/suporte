<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de Notificações
    |--------------------------------------------------------------------------
    |
    | Este arquivo contém as configurações para o sistema de notificações
    | do sistema de tickets.
    |
    */

    'email' => [
        'enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', true),
        'from_address' => env('MAIL_FROM_ADDRESS', 'sistema@tickets.com'),
        'from_name' => env('MAIL_FROM_NAME', 'Sistema de Tickets'),
        'reply_to' => env('NOTIFICATIONS_REPLY_TO', 'contato@8bits.pro'),
    ],

    'tickets' => [
        'new_ticket' => [
            'enabled' => env('NOTIFICATIONS_NEW_TICKET', true),
            'recipients' => ['admin', 'tecnico'],
            'urgent_priority' => ['alta'],
        ],
        
        'assigned' => [
            'enabled' => env('NOTIFICATIONS_TICKET_ASSIGNED', true),
            'include_ticket_details' => true,
            'include_contact_info' => true,
        ],
        
        'closed' => [
            'enabled' => env('NOTIFICATIONS_TICKET_CLOSED', true),
            'include_resolution_notes' => true,
            'include_resolution_time' => true,
        ],
        
        'urgent' => [
            'enabled' => env('NOTIFICATIONS_TICKET_URGENT', true),
            'recipients' => ['admin', 'tecnico'],
            'include_contact_phone' => true,
            'include_contact_email' => true,
        ],
        
        'replies' => [
            'enabled' => env('NOTIFICATIONS_TICKET_REPLIES', true),
            'include_reply_content' => true,
            'mark_internal_replies' => true,
        ],
        
        'status_changes' => [
            'enabled' => env('NOTIFICATIONS_STATUS_CHANGES', true),
            'notify_on' => ['em andamento', 'resolvido', 'fechado'],
        ],
        
        'priority_changes' => [
            'enabled' => env('NOTIFICATIONS_PRIORITY_CHANGES', true),
            'notify_on' => ['alta'],
        ],
    ],

    'clients' => [
        'new_client' => [
            'enabled' => env('NOTIFICATIONS_NEW_CLIENT', false),
            'recipients' => ['admin'],
        ],
        
        'client_status_change' => [
            'enabled' => env('NOTIFICATIONS_CLIENT_STATUS_CHANGE', false),
            'recipients' => ['admin'],
        ],
    ],

    'system' => [
        'maintenance' => [
            'enabled' => env('NOTIFICATIONS_MAINTENANCE', false),
            'recipients' => ['admin'],
        ],
        
        'errors' => [
            'enabled' => env('NOTIFICATIONS_ERRORS', false),
            'recipients' => ['admin'],
            'min_severity' => 'error',
        ],
    ],

    'templates' => [
        'use_custom_templates' => env('NOTIFICATIONS_USE_CUSTOM_TEMPLATES', false),
        'template_path' => env('NOTIFICATIONS_TEMPLATE_PATH', 'emails.notifications'),
    ],

    'queue' => [
        'enabled' => env('NOTIFICATIONS_QUEUE_ENABLED', true),
        'connection' => env('QUEUE_CONNECTION', 'sync'),
        'queue_name' => env('NOTIFICATIONS_QUEUE_NAME', 'notifications'),
    ],

    'rate_limiting' => [
        'enabled' => env('NOTIFICATIONS_RATE_LIMITING', true),
        'max_per_hour' => env('NOTIFICATIONS_MAX_PER_HOUR', 100),
        'max_per_user_per_hour' => env('NOTIFICATIONS_MAX_PER_USER_PER_HOUR', 10),
    ],

    'preferences' => [
        'allow_user_preferences' => env('NOTIFICATIONS_ALLOW_USER_PREFERENCES', true),
        'default_preferences' => [
            'email' => true,
            'database' => true,
            'broadcast' => false,
        ],
    ],
];

