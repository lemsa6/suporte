<?php

namespace App\Helpers;

class SystemHelper
{
    /**
     * Get system name
     */
    public static function name(): string
    {
        return config('system.name', '8BITS | SUPORTE');
    }
    
    /**
     * Get system version
     */
    public static function version(): string
    {
        return config('system.version', '1.0.0');
    }
    
    /**
     * Get system description
     */
    public static function description(): string
    {
        return config('system.description', 'Sistema de Gerenciamento de Tickets');
    }
    
    /**
     * Get company name
     */
    public static function company(): string
    {
        return config('system.company', '8Bits Pro');
    }
    
    /**
     * Get support email
     */
    public static function supportEmail(): string
    {
        return config('system.support_email', 'contato@8bits.pro');
    }
    
    /**
     * Get support phone
     */
    public static function supportPhone(): string
    {
        return config('system.support_phone', '');
    }
    
    /**
     * Get website URL
     */
    public static function website(): string
    {
        return config('system.website', 'https://8bits.pro');
    }
    
    /**
     * Get logo path
     */
    public static function logo(string $type = 'primary'): string
    {
        $logos = config('system.logo', []);
        return $logos[$type] ?? $logos['primary'] ?? 'images/8-bits-amarelo.png';
    }
    
    /**
     * Get full system title with version
     */
    public static function fullTitle(): string
    {
        return self::name() . ' v' . self::version();
    }
    
    /**
     * Check if a feature is enabled
     */
    public static function hasFeature(string $feature): bool
    {
        $features = config('system.features', []);
        return $features[$feature] ?? false;
    }
    
    /**
     * Get system limit
     */
    public static function getLimit(string $limit)
    {
        $limits = config('system.limits', []);
        return $limits[$limit] ?? null;
    }
    
    /**
     * Get formatted file size limit
     */
    public static function getFileSizeLimit(): string
    {
        $limit = self::getLimit('max_file_size');
        return $limit ? $limit . 'MB' : '25MB';
    }
    
    /**
     * Get ticket number prefix
     */
    public static function getTicketPrefix(): string
    {
        return self::getLimit('ticket_number_prefix') ?? 'TKT';
    }
}
