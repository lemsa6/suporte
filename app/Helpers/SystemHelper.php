<?php

namespace App\Helpers;

class SystemHelper
{
    /**
     * Get system name
     */
    public static function name(): string
    {
        try {
            $setting = \App\Models\Setting::where('key', 'system_name')->first();
            return $setting ? $setting->value : config('system.name', '8BITS | SUPORTE');
        } catch (\Exception $e) {
            // Se não conseguir acessar o banco, retorna o valor padrão
            return config('system.name', '8BITS | SUPORTE');
        }
    }
    
    /**
     * Get company name from settings
     */
    public static function companyName(): string
    {
        try {
            $setting = \App\Models\Setting::where('key', 'company_name')->first();
            return $setting ? $setting->value : config('app.name', 'Sistema de Suporte');
        } catch (\Exception $e) {
            // Se não conseguir acessar o banco, retorna o valor padrão
            return config('app.name', 'Sistema de Suporte');
        }
    }
    
    /**
     * Get app name (company name or system name)
     */
    public static function appName(): string
    {
        return self::companyName();
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
    
    /**
     * Retorna a classe CSS para o tipo de evento de auditoria
     */
    public static function getAuditEventColorClass(string $eventType): string
    {
        $colorMap = [
            'created' => 'verde-claro',
            'updated' => 'amarelo-claro',
            'deleted' => 'vermelho-claro',
            'replied' => 'roxo-pastel',
            'closed' => 'roxo-escuro',
            'reopened' => 'vermelho-escuro',
            'assigned' => 'azul-claro',
            'status_changed' => 'cinza-claro-2',
            'priority_changed' => 'verde-pastel',
            'viewed' => 'lilás',
            'login_success' => 'verde-claro',
            'login_failed' => 'vermelho-claro',
            'logout' => 'cinza-claro-2',
        ];

        return $colorMap[$eventType] ?? 'cinza-claro';
    }

    /**
     * Retorna a classe CSS para o status do ticket
     */
    public static function getTicketStatusColorClass(string $status): string
    {
        $colorMap = [
            'aberto' => 'amarelo-claro',      // Amarelo claro - Aberto
            'em_andamento' => 'azul-claro',   // Azul claro - Em Andamento
            'resolvido' => 'verde-claro',     // Verde claro - Resolvido
            'fechado' => 'cinza-claro-2',     // Cinza claro - Fechado
        ];

        return $colorMap[$status] ?? 'cinza-claro';
    }

    /**
     * Retorna a classe CSS para a prioridade do ticket
     */
    public static function getTicketPriorityColorClass(string $priority): string
    {
        $colorMap = [
            'baixa' => 'verde-claro',         // Verde claro - Baixa
            'média' => 'amarelo-claro',       // Amarelo claro - Média
            'alta' => 'vermelho-claro',       // Vermelho claro - Alta
        ];

        return $colorMap[$priority] ?? 'cinza-claro';
    }

    /**
     * Retorna o texto formatado para o status do ticket
     */
    public static function getTicketStatusText(string $status): string
    {
        $statusMap = [
            'aberto' => 'Aberto',
            'em_andamento' => 'Em Andamento',
            'resolvido' => 'Resolvido',
            'fechado' => 'Fechado',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Retorna o texto formatado para a prioridade do ticket
     */
    public static function getTicketPriorityText(string $priority): string
    {
        $priorityMap = [
            'baixa' => 'Baixa',
            'média' => 'Média',
            'alta' => 'Alta',
        ];

        return $priorityMap[$priority] ?? ucfirst($priority);
    }
}
