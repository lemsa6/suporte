# 📋 Changelog - Sistema de Suporte e Tickets

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

## [1.2.0] - 2025-09-06

### 🆕 Adicionado
- **Sistema de Auditoria Completo**
  - Rastreamento automático de todas as ações do sistema
  - Captura de IP real considerando proxies e load balancers
  - Registro de User Agent para análise de comportamento
  - Timestamp preciso de todas as operações
  - Relacionamento polimórfico para auditoria de qualquer modelo

- **Interface de Consulta de Auditoria**
  - Lista paginada de logs com filtros avançados
  - Filtros por tipo de evento, usuário, IP, data, modelo
  - Visualização detalhada de cada log de auditoria
  - Estatísticas de atividade do sistema
  - Exportação CSV de logs para análise externa

- **Logs Específicos por Ticket**
  - Histórico completo de ações de cada ticket
  - Botão de auditoria na visualização de tickets
  - Rastreamento de visualizações, edições e respostas

- **Tipos de Eventos Rastreados**
  - `created`: Criação de registros
  - `updated`: Atualização de registros
  - `deleted`: Exclusão de registros
  - `replied`: Respostas em tickets
  - `closed`: Fechamento de tickets
  - `reopened`: Reabertura de tickets
  - `assigned`: Atribuição de tickets
  - `status_changed`: Mudança de status
  - `priority_changed`: Mudança de prioridade
  - `viewed`: Visualização de tickets

- **Campos de Auditoria nas Tabelas**
  - `tickets`: Campos de IP e User Agent para criação, atualização e fechamento
  - `ticket_messages`: Campos de IP e User Agent para cada mensagem

- **Middleware de Captura**
  - `CaptureAuditInfo`: Captura automática de IP e User Agent
  - Detecção inteligente de IP real considerando proxies
  - Integração transparente com todas as requisições

- **Service de Auditoria**
  - `AuditService`: Gerenciamento centralizado de logs
  - Métodos específicos para cada tipo de evento
  - Consultas otimizadas com filtros e estatísticas

- **Controller de Auditoria**
  - `AuditController`: Interface completa de consulta
  - APIs REST para integração externa
  - Exportação de dados em múltiplos formatos

- **Documentação Técnica**
  - `docs/SISTEMA_AUDITORIA.md`: Documentação completa do sistema
  - Exemplos de uso e implementação
  - Guia de manutenção e extensibilidade

### 🔧 Melhorado
- **Segurança**: Rastreamento completo de acessos e modificações
- **Conformidade**: Atendimento a requisitos de auditoria
- **Transparência**: Visibilidade completa das ações realizadas
- **Análise**: Dados para análise de comportamento e performance

### 🛠️ Técnico
- **Banco de Dados**: Nova tabela `audit_logs` com índices otimizados
- **Performance**: Consultas paginadas e cache de estatísticas
- **Extensibilidade**: Sistema polimórfico para auditoria de qualquer modelo
- **Manutenibilidade**: Código modular e bem documentado

## [1.1.0] - 2025-09-05

### 🆕 Adicionado
- **Sistema de Notificações por E-mail**
  - Templates personalizáveis para diferentes tipos de notificação
  - Configuração de servidor SMTP com presets (Gmail, Outlook, etc.)
  - Editor de templates com dados dinâmicos
  - Sistema de notificações para usuários internos e clientes

- **Interface de Configurações**
  - Configurações de e-mail centralizadas
  - Editor de templates de notificação
  - Configurações de notificações por usuário
  - Dados dinâmicos da empresa para e-mails

- **Templates de E-mail**
  - Ticket criado
  - Ticket respondido
  - Status alterado
  - Ticket fechado
  - Ticket criado para cliente

### 🔧 Melhorado
- **Sistema de Notificações**: Refatoração completa para melhor performance
- **Templates**: Design responsivo e moderno
- **Configurações**: Interface intuitiva e fácil de usar
- **Validações**: Validações aprimoradas em todos os formulários

### 🐛 Corrigido
- **Links de Tickets**: Correção de links nos e-mails
- **Dados da Empresa**: Dados dinâmicos funcionando corretamente
- **Configuração de Domínio**: Configuração adequada para produção

## [1.0.0] - 2025-08-15

### 🆕 Adicionado
- **Sistema Base**
  - Autenticação e autorização com Laravel Breeze
  - Controle de acesso por roles (admin, tecnico, cliente_gestor, cliente_funcionario)
  - Gates e Policies implementados
  - Middleware de autorização granular

- **Gestão de Clientes e Contatos**
  - CRUD completo de empresas com validação de CNPJ
  - Sistema de contatos por cliente com contato principal
  - Interface AJAX para edição em tempo real
  - Sistema de status ativo/inativo

- **Sistema de Tickets**
  - CRUD completo com soft delete
  - Sistema de prioridades (baixa, média, alta)
  - Status de tickets (aberto, em_andamento, resolvido, fechado)
  - Atribuição de tickets a técnicos
  - Sistema de categorias

- **Sistema de Mensagens**
  - Respostas públicas e internas
  - Upload de anexos com validação
  - Preview de arquivos (PDF, imagens, texto)
  - Sistema de notificações

- **Dashboard e Relatórios**
  - Dashboard principal com estatísticas
  - Relatórios básicos de tickets e clientes
  - Gráficos e métricas interativas
  - Interface totalmente responsiva

- **Sistema de Anexos**
  - Upload de arquivos com validação
  - Preview de PDFs, imagens e arquivos de texto
  - Download seguro com controle de acesso
  - Modal responsivo para visualização

### 🛠️ Técnico
- **Laravel 12**: Framework PHP moderno
- **PHP 8.2+**: Linguagem de programação
- **MySQL 8.0**: Banco de dados relacional
- **Docker**: Containerização completa
- **Bootstrap 5**: Framework CSS responsivo
- **SCSS**: Pré-processador CSS
- **JavaScript Vanilla**: Interatividade sem dependências

---

## 🔗 Links Úteis

- [Documentação Completa](docs/)
- [Guia de Instalação](docs/INSTALACAO.md)
- [Guia de Uso](docs/USO_SISTEMA.md)
- [Arquitetura do Sistema](docs/ARQUITETURA.md)
- [Sistema de Auditoria](docs/SISTEMA_AUDITORIA.md)

---

**Sistema de Suporte e Tickets** - Desenvolvido com ❤️ em Laravel 12

*Mantido por: Equipe de Desenvolvimento 8Bits Pro*
