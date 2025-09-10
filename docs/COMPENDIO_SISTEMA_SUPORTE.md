# 📚 Compêndio Completo - Sistema de Suporte v1.2.6

## 🎯 **Visão Geral do Sistema**

O Sistema de Suporte e Tickets é uma plataforma completa desenvolvida em **Laravel 12** com **Tailwind CSS**, oferecendo gestão completa de tickets, clientes, notificações e auditoria.

### **Características Principais:**
- ✅ **Sistema completo de tickets** com status, prioridades e categorias
- ✅ **Gestão de clientes** com contatos hierárquicos
- ✅ **Sistema de notificações** avançado (internas + e-mail)
- ✅ **Sistema de anexos** com preview
- ✅ **Sistema de auditoria** completo com login/logout
- ✅ **Sistema de recuperação de senhas** integrado
- ✅ **Interface responsiva** com Tailwind CSS (sem Bootstrap)
- ✅ **Componentes Blade** reutilizáveis
- ✅ **Docker** para desenvolvimento e produção

---

## 🏗️ **Arquitetura do Sistema**

### **Backend (Laravel 12)**
```
app/
├── Http/Controllers/     # Controllers organizados por namespace
├── Models/              # Models Eloquent com relacionamentos
├── Services/            # Lógica de negócio centralizada
├── Mail/                # Templates de e-mail
├── Notifications/       # Notificações internas
├── Policies/            # Autorização granular
├── Middleware/          # Middleware customizado
└── View/Components/     # Componentes Blade
```

### **Frontend (Tailwind CSS + Blade)**
```
resources/
├── views/
│   ├── components/      # Componentes Blade reutilizáveis
│   ├── layouts/         # Layouts base
│   ├── admin/          # Views administrativas
│   ├── auth/           # Views de autenticação (inclui recuperação de senhas)
│   └── [resources]/    # Views por recurso
├── css/
│   └── tailwind.css    # CSS principal com sistema de cores (sem Bootstrap)
└── js/
    └── app.js          # JavaScript principal
```

### **Infraestrutura (Docker)**
```
docker/
├── php/                # Container PHP/Laravel
├── nginx/              # Web server
├── mysql/              # Banco de dados
└── redis/              # Cache
```

---

## 🎨 **Sistema de Cores e Design**

### **Paleta de Cores Principal**
```css
:root {
  --creme: #f9f9f9;              /* Fundo do site */
  --branco: #ffffff;             /* Fundo das divs */
  --roxo: #3d235a;               /* Cor principal */
  --roxo-claro: #4d2f6f;         /* Hover principal */
  --roxo-det: #7c55c3;           /* Alerta confirmação */
  --cinza: #5a5a5a;              /* Texto principal */
  --cinza-claro: #a0a0a0;        /* Texto secundário */
  --amarelo: #f0ba00;            /* Cor de destaque */
  --verde: #55c38e;              /* Alerta verde */
  --vermelho: #c35555;           /* Alerta vermelho */
}
```

### **Componentes Disponíveis**
- **`<x-button>`** - Botões com variantes (primary, secondary, outline, ghost, danger)
- **`<x-card>`** - Cards/containers com elevação
- **`<x-table>`** - Tabelas responsivas com filtros
- **`<x-input>`** - Campos de entrada com validação
- **`<x-select>`** - Seletores dropdown
- **`<x-textarea>`** - Áreas de texto
- **`<x-alert>`** - Alertas/notificações
- **`<x-badge>`** - Badges/etiquetas
- **`<x-stat-card>`** - Cards de estatísticas

---

## 👥 **Sistema de Usuários e Permissões**

### **Hierarquia de Roles**
```
admin > tecnico > cliente_gestor > cliente_funcionario
```

### **Permissões por Role**

#### **Administrador (Admin)**
- ✅ Acesso total ao sistema
- ✅ Gestão de usuários, clientes e tickets
- ✅ Configurações do sistema
- ✅ Relatórios e auditoria
- ✅ Templates de e-mail

#### **Técnico**
- ✅ Gestão de tickets atribuídos
- ✅ Respostas e atualizações
- ✅ Upload de anexos
- ✅ Relatórios básicos
- ✅ Visualização de auditoria

#### **Cliente Gestor**
- ✅ Visualização de todos os tickets da empresa
- ✅ Criação de tickets para funcionários
- ✅ Acompanhamento de status
- ✅ Gestão de contatos da empresa

#### **Cliente Funcionário**
- ✅ Visualização apenas de seus tickets
- ✅ Criação de novos tickets
- ✅ Acompanhamento de status
- ✅ Upload de anexos

---

## 🎫 **Sistema de Tickets**

### **Estados do Ticket**
- **🟡 Aberto** - Ticket recém-criado, aguardando atendimento
- **🔵 Em Andamento** - Ticket sendo trabalhado por um técnico
- **🟢 Resolvido** - Problema solucionado, aguardando confirmação
- **🔴 Fechado** - Ticket finalizado e arquivado

### **Prioridades**
- **Baixa** - Solicitações rotineiras
- **Média** - Problemas importantes
- **Alta** - Problemas críticos
- **Urgente** - Problemas que requerem atenção imediata

### **Funcionalidades**
- ✅ Criação, edição e exclusão (soft delete)
- ✅ Sistema de mensagens com histórico
- ✅ Upload de anexos com preview
- ✅ Atribuição a técnicos
- ✅ Mudança de status e prioridade
- ✅ Notificações automáticas
- ✅ Auditoria completa

---

## 📧 **Sistema de Notificações**

### **Notificações Internas (Laravel Notifications)**
- **Novo Ticket** - Para admin/técnicos
- **Ticket Atribuído** - Para técnico específico
- **Nova Resposta** - Para participantes do ticket
- **Status Alterado** - Para todos os envolvidos
- **Prioridade Alterada** - Para todos os envolvidos
- **Ticket Urgente** - Para admin/técnicos
- **Ticket Fechado** - Para todos os envolvidos

### **E-mails para Clientes (Laravel Mailables)**
- **Ticket Criado** - Confirmação de criação
- **Ticket Criado para Você** - Quando admin cria ticket
- **Nova Resposta** - Quando há resposta pública
- **Status Alterado** - Quando status muda
- **Ticket Fechado** - Quando ticket é finalizado

### **Configuração de E-mail**
```env
# Produção (Gmail SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contato@8bits.pro
MAIL_PASSWORD=sua-chave-de-aplicativo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contato@8bits.pro
MAIL_FROM_NAME="Sistema de Suporte"
```

---

## 🔐 **Sistema de Auditoria**

### **Eventos Rastreados**
- ✅ **Autenticação** - Login e logout de usuários
- ✅ **Criação** de tickets, mensagens, clientes, categorias
- ✅ **Atualização** de status, prioridade, atribuição
- ✅ **Exclusão** (soft delete) de registros
- ✅ **Respostas** em tickets
- ✅ **Fechamento** e reabertura de tickets
- ✅ **Visualização** de tickets (configurável)
- ✅ **Recuperação de senhas** - Solicitações e resets

### **Informações Capturadas**
- **Usuário** - Quem executou a ação
- **Data/Hora** - Timestamp preciso
- **IP Address** - Endereço IP real (considerando proxies)
- **User Agent** - Navegador e sistema operacional
- **URL e Método** - Localização da requisição
- **Valores** - Dados antes e depois da alteração

### **Interface de Consulta**
- **Lista de Logs** - Visualização paginada
- **Filtros Avançados** - Por tipo, usuário, IP, data
- **Detalhes Completos** - Informações detalhadas
- **Estatísticas** - Análise de atividade
- **Exportação CSV** - Para análise externa

---

## 🔑 **Sistema de Recuperação de Senhas**

### **Funcionalidades Implementadas**
- ✅ **Solicitação de Reset** - Formulário para solicitar nova senha
- ✅ **E-mail de Recuperação** - Envio de link seguro por e-mail
- ✅ **Reset de Senha** - Formulário para definir nova senha
- ✅ **Validação de Token** - Tokens seguros com expiração
- ✅ **Auditoria Completa** - Log de todas as tentativas de recuperação

### **Fluxo de Recuperação**
1. **Solicitação** - Usuário informa e-mail no formulário
2. **Validação** - Sistema verifica se e-mail existe
3. **Token** - Geração de token seguro com expiração
4. **E-mail** - Envio de link de recuperação
5. **Reset** - Usuário acessa link e define nova senha
6. **Confirmação** - Login com nova senha

### **Configuração de E-mail**
```env
# Configuração para recuperação de senhas
MAIL_FROM_ADDRESS=contato@8bits.pro
MAIL_FROM_NAME="Sistema de Suporte"
```

### **Segurança**
- ✅ **Tokens únicos** com expiração de 1 hora
- ✅ **Rate limiting** para evitar spam
- ✅ **Validação de e-mail** antes do envio
- ✅ **Logs de auditoria** para todas as tentativas
- ✅ **Invalidação** de tokens após uso

---

## 🚀 **Instalação e Configuração**

### **Pré-requisitos**
- Docker Desktop 4.0+
- Git 2.30+
- Navegador moderno

### **Instalação Rápida**
```bash
# Clone o repositório
git clone https://github.com/lemsa6/suporte.git
cd suporte

# Configure o ambiente
cp .env.example .env

# Inicie os containers
docker-compose up -d

# Configure a aplicação
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app npm run build
```

### **Acesso ao Sistema**
- **URL**: http://localhost:9000
- **Login**: admin@admin.com
- **Senha**: password
- **Recuperação de Senha**: http://localhost:9000/password/email

---

## 🛠️ **Desenvolvimento e Manutenção**

### **Comandos Úteis**
```bash
# Desenvolvimento
docker-compose exec app npm run dev

# Produção
docker-compose exec app npm run build

# Limpar cache
docker-compose exec app php artisan optimize:clear

# Ver logs
docker-compose logs app

# Backup do banco
docker-compose exec mysql mysqldump -u root -proot suporte > backup.sql
```

### **Estrutura de Banco de Dados**
```sql
-- Tabelas principais
users (1) -----> (N) tickets
clients (1) -----> (N) tickets
clients (1) -----> (N) client_contacts
tickets (1) -----> (N) ticket_messages
ticket_messages (1) -----> (N) attachments
categories (1) -----> (N) tickets

-- Sistema de auditoria
users (1) -----> (N) audit_logs
audit_logs (N) -----> (1) auditable (polimórfico)
```

---

## 📊 **Relatórios e Dashboard**

### **Dashboard Principal**
- **Estatísticas Gerais** - Total de tickets, status, prioridades
- **Gráficos** - Tickets por status, categoria, tendências
- **Atividade Recente** - Últimos tickets e ações
- **Métricas de Performance** - Tempo de resposta, taxa de resolução

### **Relatórios Disponíveis**
- **Relatório de Tickets** - Filtros por período, status, prioridade
- **Relatório de Clientes** - Atividade por empresa
- **Relatório de Auditoria** - Logs de atividade
- **Exportação** - PDF, Excel, CSV

---

## 🔒 **Segurança e Performance**

### **Segurança Implementada**
- ✅ **Autenticação Laravel Breeze** - Sistema completo de login/logout
- ✅ **Sistema de Recuperação de Senhas** - Reset seguro com tokens
- ✅ **Autorização com Gates e Policies** - Controle granular de acesso
- ✅ **Proteção CSRF** - Proteção contra ataques CSRF
- ✅ **Validação e sanitização** de dados
- ✅ **Controle de acesso granular** - Por role e permissão
- ✅ **Sistema de auditoria** completo com login/logout
- ✅ **Rate limiting** para recuperação de senhas

### **Otimizações de Performance**
- ✅ **Consultas otimizadas** com Eager Loading
- ✅ **Cache de configurações**
- ✅ **Assets comprimidos** e minificados
- ✅ **Índices de banco** otimizados
- ✅ **Containers Docker** otimizados

---

## 📱 **Responsividade e Acessibilidade**

### **Design Responsivo**
- **Mobile** - Interface adaptada para smartphones
- **Tablet** - Layout otimizado para tablets
- **Desktop** - Interface completa para desktops

### **Acessibilidade**
- ✅ Labels associados aos inputs
- ✅ Estados de foco visíveis
- ✅ Contraste adequado
- ✅ Navegação por teclado
- ✅ ARIA labels quando necessário

---

## 🎯 **Roadmap e Futuras Versões**

### **v1.3 (Próxima versão)**
- [ ] Sistema de SLA (Service Level Agreement)
- [ ] Busca avançada com filtros combinados
- [ ] Templates de resposta personalizáveis
- [ ] Sistema de tags para tickets
- [ ] Relatórios avançados com exportação

### **v1.4 (Futuro)**
- [ ] Notificações push (WebSockets)
- [ ] Integração WhatsApp Business API
- [ ] Sistema de workflows automatizados
- [ ] API REST completa
- [ ] App mobile nativo

---

## 📞 **Suporte e Contato**

### **Problemas Técnicos**
- **GitHub Issues**: [Criar issue](https://github.com/lemsa6/suporte/issues)
- **E-mail**: contato@8bits.pro
- **Documentação**: Pasta `docs/`

### **Comandos de Suporte**
```bash
# Ver logs de erro
docker-compose logs app | grep ERROR

# Testar notificações
docker-compose exec app php artisan test:notifications

# Limpar cache
docker-compose exec app php artisan optimize:clear
```

---

## 📄 **Licença e Desenvolvedor**

**Licença:** MIT  
**Desenvolvedor:** 8Bits Pro - [GitHub](https://github.com/lemsa6)

---

**Compêndio v1.2.6** - Sistema de Suporte e Tickets  
*Última atualização: Janeiro 2025*  
**Desenvolvido com ❤️ em Laravel 12 + Tailwind CSS (sem Bootstrap)**
