# Integração via OAuth com Sistema WhatsApp

Este documento descreve a implementação de integração entre o sistema de tickets e o sistema WhatsApp através de autenticação OAuth centralizada.

## 1. Arquitetura da Solução

### 1.1 Componentes Centrais
- **Servidor OAuth**: Gerencia autenticação e identidades
- **Banco de Dados Compartilhado**: Armazena IDs de usuário/cliente universais
- **API Gateway**: Gerencia comunicação entre sistemas

### 1.2 Fluxo de Autenticação
1. Cliente faz login via OAuth
2. Recebe token de acesso com identificador único
3. Sistemas usam este identificador para correlacionar dados

## 2. Implementação Técnica

### 2.1 Configuração do OAuth

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],

// config/services.php
'oauth' => [
    'server_url' => env('OAUTH_SERVER_URL', 'https://auth.8bits.pro'),
    'client_id' => env('OAUTH_CLIENT_ID'),
    'client_secret' => env('OAUTH_CLIENT_SECRET'),
    'redirect' => env('OAUTH_REDIRECT_URI'),
]
```

### 2.2 Middleware para Autenticação Compartilhada

```php
// app/Http/Middleware/SharedAuthMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;

class SharedAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken() ?? session('oauth_token');
        
        if (!$token) {
            return redirect()->route('oauth.login');
        }
        
        // Verificar token com servidor OAuth
        $response = Http::withToken($token)
            ->get(config('services.oauth.server_url') . '/api/verify');
            
        if (!$response->successful()) {
            session()->forget('oauth_token');
            return redirect()->route('oauth.login');
        }
        
        // Adicionar dados do usuário ao request
        $userData = $response->json();
        $request->merge(['oauth_user' => $userData]);
        
        return $next($request);
    }
}
```

### 2.3 Serviço de Notificação WhatsApp

```php
// app/Services/WhatsAppNotificationService.php
namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationService
{
    public function notifyTicket(Ticket $ticket, $type = 'created')
    {
        // O cliente_id já é o mesmo em ambos os sistemas
        $clientId = $ticket->client->oauth_id;
        
        // Chamar API do sistema WhatsApp
        $response = Http::withToken(config('services.whatsapp_system.api_token'))
            ->post(config('services.whatsapp_system.url') . '/api/notifications/send', [
                'client_id' => $clientId,
                'notification_type' => 'ticket_' . $type,
                'data' => [
                    'ticket_number' => $ticket->ticket_number,
                    'title' => $ticket->title,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'url' => route('tickets.show', $ticket->ticket_number)
                ]
            ]);
            
        return $response->successful();
    }
}
```

### 2.4 Controlador OAuth

```php
// app/Http/Controllers/OAuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OAuthController extends Controller
{
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => config('services.oauth.client_id'),
            'redirect_uri' => config('services.oauth.redirect'),
            'response_type' => 'code',
            'scope' => 'read write',
        ]);

        return redirect(config('services.oauth.server_url') . '/oauth/authorize?' . $query);
    }
    
    public function callback(Request $request)
    {
        $response = Http::post(config('services.oauth.server_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.oauth.client_id'),
            'client_secret' => config('services.oauth.client_secret'),
            'redirect_uri' => config('services.oauth.redirect'),
            'code' => $request->code,
        ]);
        
        $tokenData = $response->json();
        session(['oauth_token' => $tokenData['access_token']]);
        
        // Buscar dados do usuário
        $userResponse = Http::withToken($tokenData['access_token'])
            ->get(config('services.oauth.server_url') . '/api/user');
            
        $userData = $userResponse->json();
        
        // Sincronizar usuário local com dados do OAuth
        $this->syncUserData($userData);
        
        return redirect()->intended('/dashboard');
    }
    
    protected function syncUserData($userData)
    {
        // Sincronizar dados do usuário entre sistemas
        $user = \App\Models\User::updateOrCreate(
            ['oauth_id' => $userData['id']],
            [
                'name' => $userData['name'],
                'email' => $userData['email'],
                // outros campos...
            ]
        );
        
        auth()->login($user);
    }
}
```

## 3. Modelo de Dados Unificado

### 3.1 Modificação dos Modelos

```php
// app/Models/Client.php
class Client extends Model
{
    // Adicionar campo oauth_id
    protected $fillable = [
        // campos existentes...
        'oauth_id',
    ];
    
    // Relação com sistema WhatsApp via oauth_id
    public function whatsappProfile()
    {
        // Esta é uma relação "virtual" que usa API em vez de DB
        return new WhatsappProfileRelation($this);
    }
}

// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = [
        // campos existentes...
        'oauth_id',
    ];
}
```

### 3.2 Migração para Adicionar Campo OAuth ID

```php
// database/migrations/add_oauth_id_to_tables.php
Schema::table('clients', function (Blueprint $table) {
    $table->string('oauth_id')->nullable()->after('id');
    $table->index('oauth_id');
});

Schema::table('users', function (Blueprint $table) {
    $table->string('oauth_id')->nullable()->after('id');
    $table->index('oauth_id');
});
```

## 4. Comunicação entre Sistemas

### 4.1 Eventos e Webhooks

```php
// app/Events/TicketUpdated.php
class TicketUpdated implements ShouldBroadcast
{
    // Implementação existente...
    
    public function broadcastOn()
    {
        // Transmitir para canal compartilhado
        return new Channel('client.' . $this->ticket->client->oauth_id);
    }
}

// routes/api.php
Route::post('/webhooks/ticket-events', function (Request $request) {
    // Validar assinatura do webhook
    if (!$this->validateWebhookSignature($request)) {
        return response()->json(['error' => 'Invalid signature'], 403);
    }
    
    $event = $request->input('event');
    $data = $request->input('data');
    
    // Processar evento
    switch ($event) {
        case 'ticket.created':
            // Notificar via WhatsApp
            break;
        case 'ticket.updated':
            // Notificar via WhatsApp
            break;
    }
    
    return response()->json(['success' => true]);
});
```

### 4.2 API Gateway para Comunicação Direta

```php
// config/services.php
'api_gateway' => [
    'url' => env('API_GATEWAY_URL', 'https://api.8bits.pro'),
    'token' => env('API_GATEWAY_TOKEN'),
]

// app/Services/ApiGatewayService.php
class ApiGatewayService
{
    protected $http;
    
    public function __construct()
    {
        $this->http = Http::withToken(config('services.api_gateway.token'))
            ->baseUrl(config('services.api_gateway.url'));
    }
    
    public function sendWhatsAppNotification($oauthId, $message)
    {
        return $this->http->post('/whatsapp/send', [
            'oauth_id' => $oauthId,
            'message' => $message
        ]);
    }
    
    public function getClientData($oauthId)
    {
        return $this->http->get("/clients/{$oauthId}")->json();
    }
}
```

## 5. Painel de Administração Unificado

### 5.1 Características
1. **Login Único**: Administrador faz login uma vez e acessa todos os sistemas
2. **Visão Consolidada**: Dashboard mostra dados de ambos os sistemas
3. **Configurações Centralizadas**: Gerencia permissões e integrações em um só lugar

### 5.2 Interface de Administração
- Seção para gerenciar integrações entre sistemas
- Configuração de templates de mensagens WhatsApp
- Monitoramento de envios e estatísticas

## 6. Vantagens desta Abordagem

1. **Autenticação Única**: Cliente loga uma vez e tem acesso a todos os serviços
2. **Identificação Consistente**: Mesmo ID em ambos os sistemas
3. **Manutenção Simplificada**: Não precisa sincronizar dados entre sistemas
4. **Experiência Unificada**: Cliente vê tudo como um único sistema
5. **Escalabilidade**: Fácil adicionar novos serviços mantendo a identidade do cliente

## 7. Implementação Passo a Passo

1. Configurar servidor OAuth central
2. Adicionar campos `oauth_id` nas tabelas de usuários e clientes
3. Implementar middleware de autenticação compartilhada
4. Criar serviços para comunicação entre sistemas
5. Configurar eventos e webhooks
6. Implementar notificações WhatsApp usando o ID compartilhado

## 8. Requisitos de Ambiente

- PHP 8.1+
- Laravel 9+
- MySQL 8.0+
- Redis (para cache e filas)
- Servidor OAuth compatível (Laravel Passport, Keycloak, Auth0)

## 9. Considerações de Segurança

- Tokens de acesso com tempo de expiração curto
- Refresh tokens para renovação de sessão
- Validação de assinaturas em webhooks
- Criptografia de dados sensíveis
- Auditoria de todas as operações entre sistemas

## 10. Próximos Passos

1. Implementar servidor OAuth central
2. Criar migrações para adicionar campos OAuth ID
3. Desenvolver middleware de autenticação compartilhada
4. Implementar serviço de notificação WhatsApp
5. Configurar eventos e webhooks
6. Testar integração completa


