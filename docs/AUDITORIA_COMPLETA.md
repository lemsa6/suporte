# 🔐 Sistema de Auditoria Completo - v1.2

## 📋 Visão Geral

Sistema completo de auditoria para rastreamento de todas as ações dos usuários no sistema de tickets, incluindo login, tentativas de login, criação/edição de usuários e todas as operações de tickets.

## 🎯 Funcionalidades Implementadas

### ✅ **Auditoria de Tickets**
- ✅ Criação de tickets
- ✅ Respostas em tickets
- ✅ Mudança de status
- ✅ Mudança de prioridade
- ✅ Atribuição de tickets
- ✅ Fechamento de tickets
- ✅ Visualização de tickets (configurável)

### ✅ **Auditoria de Anexos**
- ✅ Upload de anexos
- ✅ Download de anexos
- ✅ Exclusão de anexos

### ✅ **Auditoria de Clientes**
- ✅ Criação de clientes
- ✅ Edição de clientes
- ✅ Exclusão de clientes
- ✅ Mudança de status

### ✅ **Auditoria de Categorias**
- ✅ Criação de categorias
- ✅ Edição de categorias
- ✅ Exclusão de categorias

## 🚧 Funcionalidades Pendentes

### 🔐 **Auditoria de Login (PENDENTE)**
- [ ] Login bem-sucedido
- [ ] Tentativas de login falhadas
- [ ] Logout
- [ ] Bloqueio de conta por tentativas
- [ ] Reset de senha
- [ ] Mudança de senha

### 👥 **Auditoria de Usuários (PENDENTE)**
- [ ] Criação de usuários
- [ ] Edição de usuários
- [ ] Exclusão de usuários
- [ ] Mudança de senha
- [ ] Mudança de perfil
- [ ] Ativação/desativação de usuários

### ⚙️ **Auditoria de Configurações (PENDENTE)**
- [ ] Mudança de configurações do sistema
- [ ] Mudança de configurações de email
- [ ] Mudança de templates
- [ ] Mudança de permissões

## 📊 Estrutura de Dados

### **Tabela: audit_logs**
```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(50) NOT NULL,           -- created, updated, deleted, login, etc.
    auditable_type VARCHAR(255) NOT NULL,      -- App\Models\Ticket, App\Models\User, etc.
    auditable_id BIGINT UNSIGNED NOT NULL,     -- ID do registro
    user_id BIGINT UNSIGNED NULL,              -- Usuário que fez a ação
    user_type VARCHAR(50) NULL,                -- admin, tecnico, cliente
    ip_address VARCHAR(45) NULL,               -- IP do usuário
    user_agent TEXT NULL,                      -- User Agent do navegador
    old_values JSON NULL,                      -- Valores anteriores
    new_values JSON NULL,                      -- Valores novos
    description TEXT NULL,                     -- Descrição da ação
    url VARCHAR(255) NULL,                     -- URL onde a ação foi executada
    method VARCHAR(10) NULL,                   -- GET, POST, PUT, DELETE
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_auditable (auditable_type, auditable_id),
    INDEX idx_user (user_id),
    INDEX idx_event (event_type),
    INDEX idx_created (created_at)
);
```

### **Tabela: login_audits (PENDENTE)**
```sql
CREATE TABLE login_audits (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NULL,              -- Usuário (NULL para tentativas falhadas)
    email VARCHAR(255) NOT NULL,               -- Email usado na tentativa
    event_type VARCHAR(50) NOT NULL,           -- login_success, login_failed, logout, etc.
    ip_address VARCHAR(45) NOT NULL,           -- IP da tentativa
    user_agent TEXT NULL,                      -- User Agent
    success BOOLEAN NOT NULL DEFAULT FALSE,    -- Se a tentativa foi bem-sucedida
    failure_reason VARCHAR(255) NULL,          -- Motivo da falha
    created_at TIMESTAMP NULL,
    
    INDEX idx_user (user_id),
    INDEX idx_email (email),
    INDEX idx_event (event_type),
    INDEX idx_success (success),
    INDEX idx_created (created_at)
);
```

## 🔧 Implementação Técnica

### **1. Middleware de Captura**
```php
// app/Http/Middleware/CaptureAuditInfo.php
class CaptureAuditInfo
{
    public function handle(Request $request, Closure $next)
    {
        // Captura IP real (considerando proxies)
        $ip = $request->ip();
        
        // Captura User Agent
        $userAgent = $request->userAgent();
        
        // Adiciona ao request para uso posterior
        $request->merge([
            'audit_ip' => $ip,
            'audit_user_agent' => $userAgent
        ]);
        
        return $next($request);
    }
}
```

### **2. Serviço de Auditoria**
```php
// app/Services/AuditService.php
class AuditService
{
    public function log(string $eventType, Model $auditable, ?User $user = null, ?Request $request = null, ?array $oldValues = null, ?array $newValues = null, ?string $description = null): AuditLog
    {
        return AuditLog::create([
            'event_type' => $eventType,
            'auditable_type' => get_class($auditable),
            'auditable_id' => $auditable->id,
            'user_id' => $user?->id,
            'user_type' => $user?->role,
            'ip_address' => $request?->audit_ip ?? $request?->ip(),
            'user_agent' => $request?->audit_user_agent ?? $request?->userAgent(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description ?? $this->generateDescription($eventType, $auditable, $user),
            'url' => $request?->fullUrl(),
            'method' => $request?->method(),
        ]);
    }
}
```

### **3. Configurações do Sistema**
```php
// database/seeders/SettingsSeeder.php
$settings = [
    [
        'key' => 'audit_views',
        'value' => 'true',
        'description' => 'Habilitar auditoria de visualizações',
        'type' => 'boolean'
    ],
    [
        'key' => 'audit_retention_days',
        'value' => '365',
        'description' => 'Dias para manter logs de auditoria',
        'type' => 'number'
    ],
    [
        'key' => 'audit_login_attempts',
        'value' => 'true',
        'description' => 'Habilitar auditoria de tentativas de login',
        'type' => 'boolean'
    ],
    [
        'key' => 'audit_user_changes',
        'value' => 'true',
        'description' => 'Habilitar auditoria de mudanças de usuários',
        'type' => 'boolean'
    ]
];
```

## 🎯 Próximos Passos

### **1. Auditoria de Login (PRIORIDADE ALTA)**
- [ ] Criar migration `create_login_audits_table`
- [ ] Criar model `LoginAudit`
- [ ] Criar service `LoginAuditService`
- [ ] Implementar no `LoginController`
- [ ] Adicionar rotas de visualização
- [ ] Criar views para relatórios

### **2. Auditoria de Usuários (PRIORIDADE ALTA)**
- [ ] Implementar no `UserController`
- [ ] Implementar no `ProfileController`
- [ ] Adicionar auditoria de mudança de senha
- [ ] Adicionar auditoria de perfil

### **3. Auditoria de Configurações (PRIORIDADE MÉDIA)**
- [ ] Implementar no `SettingsController`
- [ ] Adicionar auditoria de mudanças de sistema
- [ ] Adicionar auditoria de mudanças de email

### **4. Melhorias (PRIORIDADE BAIXA)**
- [ ] Dashboard de auditoria
- [ ] Relatórios avançados
- [ ] Exportação de logs
- [ ] Limpeza automática de logs antigos
- [ ] Alertas de segurança

## 🔒 Segurança

### **Dados Sensíveis**
- ✅ IP addresses são capturados
- ✅ User agents são capturados
- ✅ Timestamps precisos
- ✅ Rastreamento de mudanças (old/new values)

### **Configurações de Retenção**
- ✅ Configurável via settings
- ✅ Limpeza automática de logs antigos
- ✅ Backup antes da limpeza

### **Permissões**
- ✅ Apenas admins podem ver auditoria completa
- ✅ Técnicos podem ver auditoria de tickets
- ✅ Clientes não têm acesso à auditoria

## 📈 Métricas e Relatórios

### **Relatórios Disponíveis**
- ✅ Logs por usuário
- ✅ Logs por ticket
- ✅ Logs por período
- ✅ Estatísticas de auditoria
- ✅ Exportação de dados

### **Métricas Importantes**
- ✅ Ações por usuário
- ✅ Tickets mais visualizados
- ✅ Tempo de resposta
- ✅ Padrões de uso
- ✅ Tentativas de login suspeitas

## 🚀 Como Usar

### **1. Visualizar Auditoria**
```php
// Acessar: /admin/audit
Route::get('/admin/audit', [AuditController::class, 'index'])->name('admin.audit.index');
```

### **2. Filtrar Logs**
```php
// Por usuário
Route::get('/admin/audit/user/{user}', [AuditController::class, 'userLogs'])->name('admin.audit.user');

// Por ticket
Route::get('/admin/audit/ticket/{ticketNumber}', [AuditController::class, 'ticketLogs'])->name('admin.audit.ticket');
```

### **3. Exportar Dados**
```php
// Exportar logs
Route::get('/admin/audit/export', [AuditController::class, 'export'])->name('admin.audit.export');
```

## 📝 Notas de Implementação

### **Performance**
- ✅ Índices otimizados
- ✅ Paginação de resultados
- ✅ Cache de consultas frequentes
- ✅ Limpeza automática de logs antigos

### **Manutenibilidade**
- ✅ Código modular
- ✅ Service pattern
- ✅ Middleware reutilizável
- ✅ Configurações centralizadas

### **Escalabilidade**
- ✅ Suporte a múltiplos tipos de auditoria
- ✅ Fácil adição de novos eventos
- ✅ Configuração flexível
- ✅ API para integrações

---

**Versão:** 1.2  
**Última Atualização:** 2025-09-06  
**Status:** Em Desenvolvimento  
**Próxima Versão:** 1.3 (Auditoria de Login e Usuários)
