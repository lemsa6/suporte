# 🏗️ Arquitetura do Sistema de Suporte

> **📚 Para documentação completa, consulte o [Compêndio do Sistema](COMPENDIO_SISTEMA_SUPORTE.md)**

## **Visão Geral**

O Sistema de Suporte e Tickets foi desenvolvido seguindo as melhores práticas do Laravel 12, com arquitetura MVC, padrões de design e princípios SOLID.

## **📐 Estrutura da Aplicação**

### **1. Padrão MVC (Model-View-Controller)**

```
app/
├── Http/Controllers/          # Camada de Controle
│   ├── Admin/                # Controllers administrativos
│   ├── Auth/                 # Controllers de autenticação
│   └── *.php                 # Controllers específicos
├── Models/                   # Camada de Modelo
│   ├── User.php              # Modelo de usuário
│   ├── Ticket.php            # Modelo de ticket
│   ├── Client.php            # Modelo de cliente
│   └── *.php                 # Outros modelos
└── Services/                 # Camada de Serviço
    └── NotificationService.php # Serviço de notificações
```

### **2. Organização de Views**

```
resources/views/
├── layouts/                  # Layouts base
│   └── app.blade.php        # Layout principal
├── admin/                   # Views administrativas
│   └── settings/            # Configurações do sistema
├── emails/                  # Templates de e-mail
│   ├── layouts/             # Layout base para e-mails
│   └── tickets/             # Templates específicos
├── tickets/                 # Views de tickets
├── clients/                 # Views de clientes
└── auth/                    # Views de autenticação
```

## **🔐 Sistema de Autenticação e Autorização**

### **1. Roles Hierárquicos**

```php
// Hierarquia de roles
admin > tecnico > cliente_gestor > cliente_funcionario
```

### **2. Gates e Policies**

```php
// Exemplo de Gate
Gate::define('manage-system', function (User $user) {
    return $user->isAdmin();
});

// Exemplo de Policy
class TicketPolicy
{
    public function view(User $user, Ticket $ticket)
    {
        if ($user->isAdmin() || $user->isTecnico()) {
            return true;
        }
        
        if ($user->isCliente()) {
            return $ticket->client_id === $user->client_id;
        }
        
        return false;
    }
}
```

### **3. Middleware de Autorização**

```php
// Middleware personalizado
class CheckRoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            abort(403, 'Acesso negado.');
        }
        
        return $next($request);
    }
}
```

### **4. Sistema de Auditoria** 🆕

```php
// Middleware de captura de dados de auditoria
class CaptureAuditInfo
{
    public function handle(Request $request, Closure $next): Response
    {
        // Captura IP real considerando proxies
        $ipAddress = $this->getRealIpAddress($request);
        
        // Adiciona informações à requisição
        $request->merge([
            'audit_ip' => $ipAddress,
            'audit_user_agent' => $request->userAgent(),
        ]);
        
        return $next($request);
    }
}

// Service de auditoria
class AuditService
{
    public function log(string $eventType, Model $auditable, ...): AuditLog
    {
        return AuditLog::create([
            'event_type' => $eventType,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id,
            'user_id' => $user?->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            // ... outros campos
        ]);
    }
}
```

## **📊 Banco de Dados**

### **1. Relacionamentos Principais**

```sql
-- Estrutura principal
users (1) -----> (N) tickets
clients (1) -----> (N) tickets
clients (1) -----> (N) client_contacts
tickets (1) -----> (N) ticket_messages
ticket_messages (1) -----> (N) attachments
categories (1) -----> (N) tickets

-- Sistema de Auditoria 🆕
users (1) -----> (N) audit_logs
audit_logs (N) -----> (1) auditable (polimórfico)
```

### **2. Soft Deletes**

```php
// Modelo com soft delete
class Ticket extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
}
```

### **3. Migrations Organizadas**

```
database/migrations/
├── 2025_08_15_000001_create_categories_table.php
├── 2025_08_15_000002_create_clients_table.php
├── 2025_08_15_000003_create_client_contacts_table.php
├── 2025_08_15_000004_create_tickets_table.php
├── 2025_08_15_000005_create_ticket_messages_table.php
├── 2025_08_15_000006_create_attachments_table.php
├── 2025_09_05_170827_add_soft_delete_to_tickets_table.php
├── 2025_09_07_021207_create_audit_logs_table.php 🆕
├── 2025_09_07_021231_add_audit_fields_to_tickets_table.php 🆕
└── 2025_09_07_021302_add_audit_fields_to_ticket_messages_table.php 🆕
```

## **📧 Sistema de Notificações**

### **1. Arquitetura Dual**

```php
// Notificações para usuários internos (Laravel Notifications)
app/Notifications/
├── NewTicketCreated.php
├── TicketAssigned.php
├── TicketReplyNotification.php
├── TicketStatusChange.php
├── TicketPriorityChange.php
├── TicketUrgent.php
└── TicketClosed.php

// E-mails para clientes (Laravel Mailables)
app/Mail/
├── ClientTicketCreatedMail.php
├── ClientTicketRepliedMail.php
├── ClientTicketStatusChangedMail.php
└── ClientTicketClosedMail.php
```

### **2. Serviço Centralizado**

```php
class NotificationService
{
    // Notificações internas
    public function notifyNewTicket(Ticket $ticket): void
    {
        // Lógica para notificar admin/técnicos
    }
    
    // E-mails para clientes
    public function notifyClientTicketCreated(Ticket $ticket): void
    {
        Mail::to($ticket->contact->email)
            ->send(new ClientTicketCreatedMail($ticket));
    }
}
```

### **3. Templates Responsivos**

```html
<!-- Layout base para e-mails -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        /* CSS inline para compatibilidade */
    </style>
</head>
<body>
    <div class="email-container">
        @yield('content')
    </div>
</body>
</html>
```

## **🎨 Frontend e UI/UX**

### **1. Framework CSS**

- **Bootstrap 5**: Framework responsivo
- **SCSS**: Pré-processador CSS
- **CSS Inline**: Para e-mails (compatibilidade)

### **2. JavaScript**

```javascript
// Padrão de organização
// 1. Funções utilitárias
function showAlert(message, type) { ... }

// 2. Funções específicas de página
function deleteTicket(ticketNumber) { ... }

// 3. Event listeners
document.addEventListener('DOMContentLoaded', function() { ... });
```

### **3. Responsividade**

```scss
// Mobile-first approach
.container {
    padding: 1rem;
    
    @media (min-width: 768px) {
        padding: 2rem;
    }
    
    @media (min-width: 1024px) {
        padding: 3rem;
    }
}
```

## **🐳 Docker e Infraestrutura**

### **1. Docker Compose**

```yaml
version: '3.8'
services:
  app:
    build: ./docker/php
    volumes:
      - .:/var/www
    depends_on:
      - mysql
      - redis
  
  nginx:
    image: nginx:alpine
    ports:
      - "9000:80"
    volumes:
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
  
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: suporte
  
  redis:
    image: redis:alpine
```

### **2. Configurações de Ambiente**

```env
# .env
APP_NAME="Sistema de Suporte"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:9000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=suporte
DB_USERNAME=root
DB_PASSWORD=root

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-chave-de-aplicativo
```

## **🔧 Padrões de Desenvolvimento**

### **1. Controllers**

```php
class TicketController extends Controller
{
    protected $notificationService;
    
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // ... outras validações
        ]);
        
        $ticket = Ticket::create($validated);
        
        // Notificar usuários
        $this->notificationService->notifyNewTicket($ticket);
        
        return redirect()->route('tickets.show', $ticket->ticket_number)
            ->with('success', 'Ticket criado com sucesso!');
    }
}
```

### **2. Models**

```php
class Ticket extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'ticket_number',
        'title',
        'description',
        'status',
        'priority',
        // ... outros campos
    ];
    
    protected $casts = [
        'is_urgent' => 'boolean',
        'opened_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];
    
    // Relacionamentos
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    
    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
    
    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'aberto');
    }
    
    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }
}
```

### **3. Services**

```php
class NotificationService
{
    public function notifyNewTicket(Ticket $ticket): void
    {
        // Lógica de negócio centralizada
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->where('notify_ticket_created', true)
            ->get();
        
        if ($users->isNotEmpty()) {
            Notification::send($users, new NewTicketCreated($ticket));
        }
    }
}
```

## **📈 Performance e Otimização**

### **1. Consultas Otimizadas**

```php
// Eager loading para evitar N+1
$tickets = Ticket::with(['client', 'category', 'contact', 'assignedTo'])
    ->withCount(['messages', 'attachments'])
    ->latest('updated_at')
    ->paginate(20);
```

### **2. Cache**

```php
// Cache de configurações
$settings = Cache::remember('system_settings', 3600, function () {
    return Setting::all()->pluck('value', 'key');
});
```

### **3. Índices de Banco**

```sql
-- Índices para performance
CREATE INDEX idx_tickets_status ON tickets(status);
CREATE INDEX idx_tickets_priority ON tickets(priority);
CREATE INDEX idx_tickets_client_id ON tickets(client_id);
CREATE INDEX idx_tickets_created_at ON tickets(created_at);
```

## **🧪 Testes e Qualidade**

### **1. Estrutura de Testes**

```
tests/
├── Feature/                 # Testes de integração
│   ├── TicketTest.php      # Testes de tickets
│   ├── ClientTest.php      # Testes de clientes
│   └── NotificationTest.php # Testes de notificações
└── Unit/                   # Testes unitários
    ├── UserTest.php        # Testes de usuário
    └── TicketTest.php      # Testes de ticket
```

### **2. Comandos de Teste**

```bash
# Executar todos os testes
php artisan test

# Testar notificações
php artisan test:notifications

# Verificar logs
tail -f storage/logs/laravel.log
```

## **🔒 Segurança**

### **1. Validação de Dados**

```php
// Validação robusta
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'required|string|min:10',
    'priority' => 'required|in:baixa,média,alta',
    'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
]);
```

### **2. Sanitização**

```php
// Sanitização de entrada
$cleanInput = strip_tags($request->input('description'));
$cleanInput = htmlspecialchars($cleanInput, ENT_QUOTES, 'UTF-8');
```

### **3. Controle de Acesso**

```php
// Verificação de permissões
if (!$user->can('view', $ticket)) {
    abort(403, 'Acesso negado a este ticket.');
}
```

## **📚 Documentação de API**

### **1. Rotas Principais**

```php
// Rotas de tickets
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticketNumber}', [TicketController::class, 'show'])->name('tickets.show');
Route::put('/tickets/{ticketNumber}', [TicketController::class, 'update'])->name('tickets.update');
Route::delete('/tickets/{ticketNumber}', [TicketController::class, 'destroy'])->name('tickets.destroy');
```

### **2. Middleware**

```php
// Middleware de autenticação
Route::middleware(['auth'])->group(function () {
    // Rotas protegidas
});

// Middleware de role
Route::middleware(['role:admin,tecnico'])->group(function () {
    // Rotas para admin e técnicos
});
```

## **🚀 Deploy e Produção**

### **1. Configurações de Produção**

```env
# .env.production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com

# Banco de dados de produção
DB_HOST=seu-host-mysql
DB_DATABASE=suporte_prod
DB_USERNAME=usuario_prod
DB_PASSWORD=senha_segura

# E-mail de produção
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=contato@seu-dominio.com
MAIL_PASSWORD=chave-de-aplicativo
```

### **2. Otimizações de Produção**

```bash
# Comandos para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

**Arquitetura do Sistema v1.1** - Desenvolvido com ❤️ em Laravel 12

*Última atualização: 05/09/2025*
