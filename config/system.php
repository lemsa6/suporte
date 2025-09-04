<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Information
    |--------------------------------------------------------------------------
    |
    | This file contains centralized system information that can be used
    | throughout the application.
    |
    */

    'name' => env('APP_NAME', '8BITS | SUPORTE'),
    
    'version' => env('APP_VERSION', '1.0.0'),
    
    'description' => env('APP_DESCRIPTION', 'Sistema de Gerenciamento de Tickets'),
    
    'company' => env('APP_COMPANY', '8Bits Pro'),
    
    'support_email' => env('MAIL_FROM_ADDRESS', 'contato@8bits.pro'),
    
    'support_phone' => env('APP_SUPPORT_PHONE', ''),
    
    'website' => env('APP_WEBSITE', 'https://8bits.pro'),
    
    'logo' => [
        'primary' => 'images/8-bits-amarelo.png',
        'white' => 'images/8bits-branco.png',
        'favicon' => 'images/favicon.ico',
    ],
    
    'features' => [
        'tickets' => true,
        'clients' => true,
        'categories' => true,
        'reports' => true,
        'notifications' => true,
        'file_attachments' => true,
        'multi_tenant' => false,
    ],
    
    'limits' => [
        'max_file_size' => env('MAX_FILE_SIZE', 25), // MB
        'max_files_per_ticket' => env('MAX_FILES_PER_TICKET', 10),
        'ticket_number_prefix' => env('TICKET_PREFIX', 'TKT'),
    ],

];
