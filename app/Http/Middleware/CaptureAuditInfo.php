<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureAuditInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Capturar informações de auditoria
        $this->captureAuditInfo($request);
        
        return $next($request);
    }

    /**
     * Captura informações de auditoria da requisição
     */
    private function captureAuditInfo(Request $request): void
    {
        // Obter IP real do usuário (considerando proxies)
        $ipAddress = $this->getRealIpAddress($request);
        
        // Obter User Agent
        $userAgent = $request->userAgent();
        
        // Adicionar informações à requisição para uso posterior
        $request->merge([
            'audit_ip' => $ipAddress,
            'audit_user_agent' => $userAgent,
        ]);
    }

    /**
     * Obtém o IP real do usuário, considerando proxies e load balancers
     */
    private function getRealIpAddress(Request $request): string
    {
        // Lista de headers que podem conter o IP real
        $ipHeaders = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_CLIENT_IP',            // Proxy
            'HTTP_X_FORWARDED_FOR',      // Load balancer/proxy
            'HTTP_X_FORWARDED',          // Load balancer/proxy
            'HTTP_X_CLUSTER_CLIENT_IP',  // Cluster
            'HTTP_FORWARDED_FOR',        // Proxy
            'HTTP_FORWARDED',            // Proxy
            'HTTP_VIA',                  // Proxy
            'REMOTE_ADDR'                // IP direto
        ];

        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                
                // Validar se é um IP válido
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        // Fallback para o IP da requisição
        return $request->ip() ?? '0.0.0.0';
    }
}