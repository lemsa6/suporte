# Documentação de Configurações do Sistema

## Visão Geral

O sistema de tickets possui uma seção de configurações que permite personalizar diversos aspectos do sistema, incluindo o formato dos tickets e as configurações de email para notificações. Este documento descreve as principais funcionalidades e como implementá-las.

## 1. Formato de Tickets

### 1.1. Estrutura do Número de Ticket

Os tickets no sistema seguem um formato estruturado:

```
PREFIXO-ANO-NÚMERO
```

Exemplo: `TKT-2025-0001`

Onde:
- **PREFIXO**: Um código curto que identifica o tipo de ticket (padrão: "TKT")
- **ANO**: O ano atual em 4 dígitos (ex: 2025)
- **NÚMERO**: Um número sequencial com zeros à esquerda (ex: 0001)

### 1.2. Personalização do Prefixo

O sistema permite personalizar o prefixo dos tickets através da página de configurações. Isso é útil para:

- Adaptar o sistema à identidade da empresa (ex: "8BT" para "8BITS")
- Diferenciar tipos de tickets (ex: "SUP" para suporte, "DEV" para desenvolvimento)
- Criar múltiplas instâncias do sistema com identificadores diferentes

**Importante**: A alteração do prefixo não afeta tickets já criados, mantendo a compatibilidade com links existentes.

### 1.3. Implementação Técnica

O prefixo é armazenado na tabela `settings` com a chave `ticket_prefix` e pode ser acessado através do modelo `Setting`:

```php
// Obter o prefixo atual
$prefix = Setting::get('ticket_prefix', 'TKT');

// Gerar um novo número de ticket
$ticketNumber = Ticket::generateTicketNumber($prefix);
```

## 2. Configurações de Email

### 2.1. Opções de Configuração

O sistema suporta múltiplas opções para envio de emails:

#### 2.1.1. SMTP Genérico

Permite configurar qualquer servidor SMTP com os seguintes parâmetros:
- Servidor SMTP (ex: smtp.seudominio.com)
- Porta (25, 465, 587, etc.)
- Segurança (SSL/TLS/STARTTLS)
- Usuário/email
- Senha ou chave de API
- Email de remetente
- Nome de exibição do remetente

#### 2.1.2. Serviços Populares

Configurações pré-definidas para serviços comuns:
- Gmail
- Microsoft 365/Outlook
- Amazon SES
- SendGrid
- Mailgun

#### 2.1.3. Métodos de Autenticação

Suporte a diferentes métodos de autenticação:
- Autenticação básica (usuário/senha)
- OAuth para serviços compatíveis (Gmail, Microsoft 365)
- Chaves de API para serviços como SendGrid, Mailgun, etc.

### 2.2. Interface de Configuração

A interface de configuração de email permite:
- Testar a conexão com o servidor SMTP
- Enviar um email de teste
- Salvar as configurações no banco de dados
- Escolher modelos de email para diferentes notificações

### 2.3. Implementação Técnica

As configurações de email são armazenadas na tabela `settings` com prefixo `email_` e podem ser acessadas através do modelo `Setting`:

```php
// Obter configurações de email
$smtpHost = Setting::get('email_smtp_host');
$smtpPort = Setting::get('email_smtp_port');
$smtpUser = Setting::get('email_smtp_user');
// etc.

// Configurar o serviço de email
config([
    'mail.mailers.smtp.host' => $smtpHost,
    'mail.mailers.smtp.port' => $smtpPort,
    'mail.mailers.smtp.username' => $smtpUser,
    // etc.
]);
```

## 3. Página de Configurações

### 3.1. Estrutura da Página

A página de configurações é acessível apenas para administradores e está organizada em abas:

1. **Geral**
   - Nome do sistema
   - Logo
   - Informações da empresa

2. **Tickets**
   - Prefixo de tickets
   - Numeração
   - Campos personalizados

3. **Email**
   - Configuração SMTP
   - Modelos de email
   - Testes de conexão

4. **Usuários**
   - Políticas de senha
   - Tempo de inatividade
   - Configurações de registro

### 3.2. Permissões

Apenas usuários com a permissão `manage-system` podem acessar e modificar as configurações do sistema.

## 4. Implementação Futura

### 4.1. Configurações Adicionais

Futuras versões do sistema poderão incluir:
- Integração com serviços de armazenamento em nuvem (Google Drive, OneDrive, etc.)
- Configurações de backup automático
- Personalização de temas e cores
- Integração com ferramentas de terceiros (Slack, Teams, etc.)

### 4.2. API de Configurações

Uma API para gerenciar configurações programaticamente, permitindo:
- Automação de configurações
- Integração com outros sistemas
- Backup e restauração de configurações


