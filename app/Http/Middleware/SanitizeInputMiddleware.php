<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitizar dados de entrada
        $this->sanitizeInput($request);
        
        return $next($request);
    }
    
    /**
     * Sanitize input data
     */
    protected function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        
        // Sanitizar strings
        $input = $this->sanitizeStrings($input);
        
        // Remover tags HTML perigosas
        $input = $this->removeDangerousTags($input);
        
        // Limitar tamanho de campos
        $input = $this->limitFieldSizes($input);
        
        $request->merge($input);
    }
    
    /**
     * Sanitize string values
     */
    protected function sanitizeStrings($data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remover caracteres de controle
                $data[$key] = preg_replace('/[\x00-\x1F\x7F]/', '', $value);
                
                // Normalizar espaços em branco
                $data[$key] = preg_replace('/\s+/', ' ', trim($value));
                
                // Escapar caracteres especiais para HTML
                $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($value)) {
                $data[$key] = $this->sanitizeStrings($value);
            }
        }
        
        return $data;
    }
    
    /**
     * Remove dangerous HTML tags
     */
    protected function removeDangerousTags($data): array
    {
        $dangerousTags = [
            'script', 'iframe', 'object', 'embed', 'form', 'input', 'button',
            'link', 'meta', 'style', 'base', 'applet', 'frame', 'frameset'
        ];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remover tags perigosas
                foreach ($dangerousTags as $tag) {
                    $data[$key] = preg_replace('/<\/?' . $tag . '[^>]*>/i', '', $value);
                }
                
                // Remover atributos perigosos
                $data[$key] = preg_replace('/\s+(on\w+|javascript:|data:|vbscript:)[^=]*=[^"\'>\s]+/i', '', $value);
            } elseif (is_array($value)) {
                $data[$key] = $this->removeDangerousTags($value);
            }
        }
        
        return $data;
    }
    
    /**
     * Limit field sizes
     */
    protected function limitFieldSizes($data): array
    {
        $limits = [
            'name' => 255,
            'email' => 255,
            'title' => 255,
            'description' => 5000,
            'message' => 5000,
            'notes' => 1000,
            'address' => 500,
            'phone' => 20,
            'cnpj' => 18,
            'company_name' => 255,
            'trade_name' => 255,
        ];
        
        foreach ($data as $key => $value) {
            if (is_string($value) && isset($limits[$key])) {
                $data[$key] = substr($value, 0, $limits[$key]);
            } elseif (is_array($value)) {
                $data[$key] = $this->limitFieldSizes($value);
            }
        }
        
        return $data;
    }
}