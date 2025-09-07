# Sistema de Auditoria - Documentação Técnica

## Visão Geral

O Sistema de Auditoria foi implementado para rastrear todas as ações realizadas no sistema de tickets, capturando informações detalhadas sobre quem, quando, onde e como cada ação foi executada.

## Funcionalidades Implementadas

### 1. Captura Automática de Dados
- **IP Address**: Captura o endereço IP real do usuário (considerando proxies e load balancers)
- **User Agent**: Registra informações do navegador e sistema operacional
- **Data/Hora**: Timestamp preciso de cada ação
- **Usuário**: Identificação do usuário que executou a ação
- **URL e Método HTTP**: Localização e método da requisição

### 2. Tipos de Eventos Rastreados
- **created**: Criação de registros (tickets, mensagens, etc.)
- **updated**: Atualização de registros
- **deleted**: Exclusão de registros
- **replied**: Respostas em tickets
- **closed**: Fechamento de tickets
- **reopened**: Reabertura de tickets
- **assigned**: Atribuição de tickets
- **status_changed**: Mudança de status
- **priority_changed**: Mudança de prioridade
- **viewed**: Visualização de tickets

### 3. Interface de Consulta
- **Lista de Logs**: Visualização paginada de todos os logs
- **Filtros Avançados**: Por tipo de evento, usuário, IP, data, etc.
- **Detalhes Completos**: Visualização detalhada de cada log
- **Estatísticas**: Análise de atividade do sistema
- **Exportação CSV**: Exportação de dados para análise externa

## Estrutura do Banco de Dados

### Tabela `audit_logs`
```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    event_type VARCHAR(255) NOT NULL,
    auditable_type VARCHAR(255) NOT NULL,
    auditable_id BIGINT NOT NULL,
    user_id BIGINT NULL,
    user_type VARCHAR(255) NULL,
    ip_address VARCHAR(255) NULL,
    user_agent TEXT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    description TEXT NULL,
    url VARCHAR(255) NULL,
    method VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_auditable (auditable_type, auditable_id),
    INDEX idx_user (user_id),
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at)
);
```

### Campos Adicionais nas Tabelas Existentes

#### Tabela `tickets`
- `created_ip`: IP de criação
- `created_user_agent`: User Agent de criação
- `updated_ip`: IP da última atualização
- `updated_user_agent`: User Agent da última atualização
- `closed_ip`: IP de fechamento
- `closed_user_agent`: User Agent de fechamento

#### Tabela `ticket_messages`
- `ip_address`: IP da mensagem
- `user_agent`: User Agent da mensagem

## Arquitetura do Sistema

### 1. Model `AuditLog`
```php
class AuditLog extends Model
{
    // Relacionamentos
    public function user(): BelongsTo
    public function auditable(): MorphTo
    
    // Scopes
    public function scopeEventType($query, $eventType)
    public function scopeByUser($query, $userId)
    public function scopeAuditable($query, $type, $id = null)
    public function scopeDateRange($query, $startDate, $endDate)
    public function scopeByIp($query, $ipAddress)
    
    // Accessors
    public function getUserNameAttribute(): string
    public function getUserEmailAttribute(): ?string
    public function getBrowserInfoAttribute(): string
    public function getFormattedDescriptionAttribute(): string
}
```

### 2. Service `AuditService`
```php
class AuditService
{
    // Métodos principais
    public function log(string $eventType, Model $auditable, ...): AuditLog
    public function logCreated(Model $auditable, ...): AuditLog
    public function logUpdated(Model $auditable, ...): AuditLog
    public function logDeleted(Model $auditable, ...): AuditLog
    public function logTicketReply(Model $ticket, Model $message, ...): AuditLog
    public function logTicketClosed(Model $ticket, ...): AuditLog
    public function logTicketReopened(Model $ticket, ...): AuditLog
    public function logTicketAssigned(Model $ticket, ...): AuditLog
    public function logStatusChange(Model $auditable, ...): AuditLog
    public function logPriorityChange(Model $auditable, ...): AuditLog
    public function logTicketViewed(Model $ticket, ...): AuditLog
    
    // Consultas
    public function getLogs(array $filters = []): Builder
    public function getStatistics(array $filters = []): array
}
```

### 3. Middleware `CaptureAuditInfo`
```php
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
```

### 4. Controller `AuditController`
```php
class AuditController extends Controller
{
    public function index(Request $request): View      // Lista de logs
    public function show(AuditLog $auditLog): View    // Detalhes do log
    public function ticketLogs(string $ticketNumber): View  // Logs de um ticket
    public function statistics(Request $request): View      // Estatísticas
    public function export(Request $request)               // Exportação CSV
    public function apiLogs(Request $request): JsonResponse // API para logs
    public function apiStatistics(Request $request): JsonResponse // API para estatísticas
}
```

## Implementação nos Controllers

### TicketController
```php
// No método store()
$this->auditService->logCreated($ticket, auth()->user(), $request);
$this->auditService->logTicketReply($ticket, $ticketMessage, auth()->user(), $request);

// No método show()
$this->auditService->logTicketViewed($ticket, auth()->user(), request());

// No método update()
$this->auditService->logUpdated($ticket, $oldValues, $ticket->toArray(), auth()->user(), $request);
$this->auditService->logStatusChange($ticket, $oldStatus, $newStatus, auth()->user(), $request);
$this->auditService->logPriorityChange($ticket, $oldPriority, $newPriority, auth()->user(), $request);
$this->auditService->logTicketAssigned($ticket, $assignedUser, auth()->user(), $request);
$this->auditService->logTicketClosed($ticket, auth()->user(), $request);

// No método addMessage()
$this->auditService->logTicketReply($ticket, $ticketMessage, $user, $request);
```

## Rotas Implementadas

```php
Route::prefix('audit')->name('audit.')->group(function () {
    Route::get('/', [AuditController::class, 'index'])->name('index');
    Route::get('/statistics', [AuditController::class, 'statistics'])->name('statistics');
    Route::get('/export', [AuditController::class, 'export'])->name('export');
    Route::get('/ticket/{ticketNumber}', [AuditController::class, 'ticketLogs'])->name('ticket');
    Route::get('/{auditLog}', [AuditController::class, 'show'])->name('show');
    
    // APIs
    Route::get('/api/logs', [AuditController::class, 'apiLogs'])->name('api.logs');
    Route::get('/api/statistics', [AuditController::class, 'apiStatistics'])->name('api.statistics');
});
```

## Views Implementadas

### 1. `admin.audit.index`
- Lista paginada de logs de auditoria
- Filtros avançados (tipo de evento, usuário, IP, data, etc.)
- Botões de ação (ver detalhes, exportar)
- Design responsivo e moderno

### 2. `admin.audit.show`
- Detalhes completos de um log específico
- Informações do usuário, IP, User Agent
- Valores antigos e novos (quando aplicável)
- Informações do modelo relacionado

### 3. `admin.audit.statistics`
- Estatísticas gerais do sistema
- Gráficos de atividade por tipo de evento
- Usuários mais ativos
- Atividade recente

### 4. `admin.audit.ticket`
- Logs específicos de um ticket
- Histórico completo de ações
- Informações do ticket relacionado

## Segurança e Privacidade

### 1. Controle de Acesso
- Apenas administradores e técnicos podem acessar os logs de auditoria
- Filtros por usuário para limitar visualização quando necessário

### 2. Dados Sensíveis
- IPs são capturados para rastreamento de segurança
- User Agents são armazenados para análise de comportamento
- Dados antigos e novos são preservados para auditoria completa

### 3. Retenção de Dados
- Logs são mantidos indefinidamente (configurável)
- Possibilidade de implementar limpeza automática por idade

## Performance

### 1. Índices de Banco
- Índices otimizados para consultas frequentes
- Índices compostos para filtros múltiplos

### 2. Paginação
- Lista de logs paginada (20 por página)
- Carregamento lazy de relacionamentos

### 3. Cache
- Possibilidade de implementar cache para estatísticas
- Cache de consultas frequentes

## Monitoramento e Alertas

### 1. Métricas Disponíveis
- Total de logs por período
- Atividade por usuário
- Tipos de eventos mais frequentes
- IPs mais ativos

### 2. Alertas Possíveis
- Múltiplas ações de um mesmo IP
- Ações suspeitas fora do horário comercial
- Tentativas de acesso não autorizado

## Extensibilidade

### 1. Novos Tipos de Eventos
- Fácil adição de novos tipos de eventos
- Métodos específicos no AuditService

### 2. Novos Modelos
- Sistema polimórfico permite auditoria de qualquer modelo
- Configuração automática via traits

### 3. Integrações
- APIs REST para integração externa
- Exportação em múltiplos formatos
- Webhooks para notificações em tempo real

## Manutenção

### 1. Limpeza de Dados
```php
// Exemplo de limpeza de logs antigos
AuditLog::where('created_at', '<', now()->subMonths(6))->delete();
```

### 2. Backup
- Logs incluídos no backup regular do banco
- Exportação periódica para arquivo

### 3. Monitoramento
- Logs de erro para falhas na auditoria
- Métricas de performance das consultas

## Conclusão

O Sistema de Auditoria fornece uma solução completa para rastreamento de atividades no sistema de tickets, oferecendo:

- **Transparência**: Visibilidade completa das ações realizadas
- **Segurança**: Rastreamento de acessos e modificações
- **Conformidade**: Atendimento a requisitos de auditoria
- **Análise**: Dados para análise de comportamento e performance
- **Extensibilidade**: Fácil adição de novos recursos

O sistema está pronto para uso em produção e pode ser facilmente estendido conforme necessário.
