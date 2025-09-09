<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SecurityService
{
    /**
     * Validate password strength
     */
    public function validatePasswordStrength(string $password): array
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra maiúscula.';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos uma letra minúscula.';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos um número.';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'A senha deve conter pelo menos um caractere especial.';
        }
        
        return $errors;
    }
    
    /**
     * Check if password is compromised
     */
    public function isPasswordCompromised(string $password): bool
    {
        // Lista de senhas comuns que devem ser evitadas
        $commonPasswords = [
            '123456', 'password', '123456789', '12345678', '12345',
            '1234567', '1234567890', 'qwerty', 'abc123', '111111',
            '123123', 'admin', 'letmein', 'welcome', 'monkey',
            '1234', 'dragon', 'master', 'hello', 'login'
        ];
        
        return in_array(strtolower($password), $commonPasswords);
    }
    
    /**
     * Validate file upload security
     */
    public function validateFileUpload($file, array $allowedTypes = [], int $maxSize = 25000): array
    {
        $errors = [];
        
        // Check file size
        if ($file->getSize() > $maxSize * 1024) {
            $errors[] = "O arquivo deve ter no máximo {$maxSize}KB.";
        }
        
        // Check file type
        $allowedMimes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'zip' => 'application/zip',
            'txt' => 'text/plain',
            'log' => 'text/plain',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        
        if (!empty($allowedTypes)) {
            $allowedMimes = array_intersect_key($allowedMimes, array_flip($allowedTypes));
        }
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            $errors[] = 'Tipo de arquivo não permitido.';
        }
        
        // Check for malicious content
        $content = file_get_contents($file->getPathname());
        if ($this->containsMaliciousContent($content)) {
            $errors[] = 'Arquivo contém conteúdo suspeito.';
        }
        
        return $errors;
    }
    
    /**
     * Check for malicious content in files
     */
    protected function containsMaliciousContent(string $content): bool
    {
        $maliciousPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            '/onmouseover\s*=/i',
            '/eval\s*\(/i',
            '/document\.write/i',
            '/innerHTML\s*=/i',
            '/outerHTML\s*=/i',
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Validate CSRF token
     */
    public function validateCsrfToken(Request $request): bool
    {
        $token = $request->header('X-CSRF-TOKEN') ?: $request->input('_token');
        
        if (!$token) {
            return false;
        }
        
        return hash_equals(session()->token(), $token);
    }
    
    /**
     * Check rate limiting
     */
    public function checkRateLimit(string $key, int $maxAttempts = 60, int $decayMinutes = 1): bool
    {
        return RateLimiter::tooManyAttempts($key, $maxAttempts);
    }
    
    /**
     * Hit rate limiter
     */
    public function hitRateLimit(string $key, int $decayMinutes = 1): int
    {
        return RateLimiter::hit($key, $decayMinutes * 60);
    }
    
    /**
     * Get remaining attempts
     */
    public function getRemainingAttempts(string $key): int
    {
        return RateLimiter::remaining($key, 60);
    }
    
    /**
     * Clear rate limiter
     */
    public function clearRateLimit(string $key): void
    {
        RateLimiter::clear($key);
    }
    
    /**
     * Validate IP address
     */
    public function isValidIpAddress(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * Check if IP is blacklisted
     */
    public function isIpBlacklisted(string $ip): bool
    {
        $blacklistedIps = config('security.blacklisted_ips', []);
        
        return in_array($ip, $blacklistedIps);
    }
    
    /**
     * Generate secure random string
     */
    public function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Validate email format and domain
     */
    public function validateEmailSecurity(string $email): array
    {
        $errors = [];
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Formato de email inválido.';
            return $errors;
        }
        
        $domain = substr(strrchr($email, "@"), 1);
        
        // Check for disposable email domains
        $disposableDomains = [
            '10minutemail.com', 'tempmail.org', 'guerrillamail.com',
            'mailinator.com', 'yopmail.com', 'temp-mail.org'
        ];
        
        if (in_array($domain, $disposableDomains)) {
            $errors[] = 'Domínio de email temporário não permitido.';
        }
        
        return $errors;
    }
    
    /**
     * Sanitize user input
     */
    public function sanitizeInput(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Remove control characters
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Normalize whitespace
        $input = preg_replace('/\s+/', ' ', trim($input));
        
        // Escape HTML
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        return $input;
    }
}
