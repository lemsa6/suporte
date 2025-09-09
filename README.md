# 🎫 Sistema de Suporte e Tickets - v1.2

Sistema completo de gerenciamento de tickets de suporte desenvolvido em Laravel 12, com interface responsiva, sistema de notificações avançado, **sistema de auditoria completo** e funcionalidades para empresas e clientes.

## 🚀 **Funcionalidades Principais**

### ✅ **Sistema de Autenticação e Autorização**
- Login/Logout com Laravel Breeze
- Controle de acesso por roles (admin, tecnico, cliente_gestor, cliente_funcionario)
- Gates e Policies implementados
- Middleware de autorização granular

### ✅ **Gestão de Clientes e Contatos**
- CRUD completo de empresas com validação de CNPJ
- Sistema de contatos por cliente com contato principal
- Interface AJAX para edição em tempo real
- Sistema de status ativo/inativo

### ✅ **Sistema de Tickets Avançado**
- CRUD completo com soft delete
- Sistema de status (aberto, em andamento, resolvido, fechado)
- Sistema de prioridade (baixa, média, alta) com notificações
- Atribuição de responsáveis
- Histórico completo de mensagens
- Sistema de anexos com preview (PDF, imagens, texto)
- Soft delete para preservar histórico

### ✅ **Sistema de Notificações por E-mail**
- **7 tipos de notificações** para usuários internos (admin/técnicos)
- **5 tipos de notificações** para clientes via Mailable
- Templates personalizados com dados dinâmicos da empresa
- Configuração Gmail SMTP com App Passwords
- Painel de configurações para templates e dados da empresa
- Links corretos de tickets nos e-mails

### ✅ **Sistema de Roles Hierárquicos**
- **Admin**: Acesso total ao sistema
- **Técnico**: Gestão de tickets atribuídos
- **Cliente Gestor**: Vê todos os tickets da empresa
- **Cliente Funcionário**: Vê apenas seus próprios tickets
- Dashboard adaptativo baseado no role

### ✅ **Painel de Configurações Administrativas**
- Configurações gerais do sistema
- Configuração de e-mail com presets (Gmail, Outlook, etc.)
- Editor de templates de notificação
- Configurações de notificações
- Dados dinâmicos da empresa para e-mails

### ✅ **Sistema de Anexos Avançado**
- Upload de arquivos com validação
- Preview de PDFs, imagens e arquivos de texto
- Download seguro com controle de acesso
- Modal responsivo para visualização

### ✅ **Sistema de Auditoria Completo** 🆕
- **Rastreamento Automático**: Todas as ações são registradas automaticamente
- **Captura de Dados**: IP real, User Agent, data/hora, usuário, URL
- **Tipos de Eventos**: Criação, atualização, exclusão, respostas, fechamento, visualização
- **Interface de Consulta**: Lista paginada com filtros avançados
- **Estatísticas**: Análise de atividade do sistema
- **Exportação CSV**: Dados para análise externa
- **Segurança**: Rastreamento de IPs e User Agents para auditoria de segurança

### ✅ **Dashboard e Relatórios**
- Dashboard principal com estatísticas em tempo real
- Relatórios básicos de tickets e clientes
- Gráficos e métricas interativas
- Interface totalmente responsiva

### ✅ **Sistema de Componentes Blade** 🆕
- **10+ Componentes Reutilizáveis**: Button, Card, Table, Input, Select, etc.
- **Tailwind CSS**: Framework CSS utilitário moderno
- **Fonte Lato**: Sistema tipográfico consistente
- **Design System**: Classes semânticas para manutenção fácil
- **Responsividade**: Mobile-first design
- **Acessibilidade**: Padrões WCAG 2.1

## 🛠 **Tecnologias Utilizadas**

### **Backend**
- **Laravel 12** - Framework PHP moderno
- **PHP 8.2+** - Linguagem de programação
- **MySQL 8.0** - Banco de dados relacional
- **Docker** - Containerização completa

### **Frontend**
- **Tailwind CSS** - Framework CSS utilitário moderno
- **Componentes Blade** - Sistema de componentes reutilizáveis
- **Lato Font** - Fonte principal do sistema (local)
- **JavaScript Vanilla** - Interatividade sem dependências
- **AJAX** - Requisições assíncronas
- **Vite** - Build tool moderno para assets

### **Infraestrutura**
- **Docker Compose** - Orquestração de containers
- **Nginx** - Web server otimizado
- **Mailpit** - Servidor de e-mail para desenvolvimento
- **Gmail SMTP** - Servidor de e-mail para produção

## 📋 **Pré-requisitos**

- Docker e Docker Compose
- Git
- Navegador moderno (Chrome, Firefox, Safari, Edge)

## 🚀 **Instalação Rápida**

### **1. Clone o repositório**
```bash
git clone https://github.com/lemsa6/suporte.git
cd suporte
```

### **2. Configure o ambiente**
```bash
# Copie o arquivo de configuração
cp .env.example .env

# Configure as variáveis de ambiente
# Edite o arquivo .env com suas configurações
```

### **3. Execute com Docker**
```bash
# Inicie os containers
docker-compose up -d

# Instale as dependências
docker-compose exec app composer install
docker-compose exec app npm install

# Configure o banco de dados
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed

# Compile os assets
docker-compose exec app npm run build
```

### **4. Acesse o sistema**
- **URL**: http://localhost:9000
- **Login padrão**: admin@admin.com / password

## 🚀 **Deploy em Produção**

### **1. Deploy automático (Recomendado):**
```bash
# Linux/Mac
chmod +x deploy.sh
./deploy.sh

# Windows
deploy.bat
```

### **1.1. Deploy manual:**
```bash
# Instale as dependências PHP
composer install --optimize-autoloader --no-dev

# Instale as dependências Node.js
npm install

# Compile os assets para produção
npm run build

# Configure as permissões (Linux/Mac)
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### **2. Configurações importantes:**
- ✅ **Chart.js**: Instalado localmente (não usa CDN)
- ✅ **Tailwind CSS**: Compilado via Vite
- ✅ **Fontes Lato**: Carregadas localmente
- ✅ **Sem CDN**: Sistema 100% offline

### **3. Verificação pós-deploy:**
```bash
# Verifique se os assets foram compilados
ls -la public/build/assets/

# Deve conter:
# - tailwind-[hash].css
# - app-[hash].js (incluindo Chart.js)
# - manifest.json
```

### **4. Dependências importantes:**
```json
{
  "dependencies": {
    "chart.js": "^4.5.0",  // Gráficos (instalado localmente)
    "bootstrap": "^5.3.2"   // Componentes (não carregado via CDN)
  },
  "devDependencies": {
    "tailwindcss": "^3.4.17",  // CSS Framework
    "vite": "^4.0.0"           // Build tool
  }
}
```

> ⚠️ **IMPORTANTE**: O sistema não usa CDN. Todas as dependências são instaladas localmente e compiladas via Vite.

## 📱 **Responsividade**

O sistema é totalmente responsivo e otimizado para:
- 📱 **Mobile** (320px+) - Interface adaptada para touch
- 📱 **Tablet** (768px+) - Layout híbrido
- 💻 **Desktop** (1024px+) - Interface completa

## 🔧 **Configuração de E-mail**

### **Gmail (Recomendado)**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-chave-de-aplicativo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Sistema de Suporte"
```

**Importante:** Use uma [Chave de Aplicativo](https://myaccount.google.com/apppasswords) do Gmail, não sua senha normal.

### **Outros Provedores**
O sistema inclui presets para:
- Outlook/Hotmail
- Yahoo Mail
- SendGrid
- Mailgun
- Amazon SES

## 🧩 **Sistema de Componentes**

### **Componentes Disponíveis**
- `<x-button>` - Botões com variantes e tamanhos
- `<x-card>` - Cards/containers flexíveis
- `<x-stat-card>` - Cards de estatísticas
- `<x-table>` - Tabelas responsivas
- `<x-input>` - Campos de entrada
- `<x-select>` - Seletores dropdown
- `<x-textarea>` - Áreas de texto
- `<x-alert>` - Alertas/notificações
- `<x-badge>` - Badges/etiquetas
- `<x-menu-item>` - Itens de menu

### **Exemplo de Uso**
```blade
<!-- Dashboard com componentes -->
<x-card title="Minha Página">
    <x-button variant="primary" size="lg">
        Ação Principal
    </x-button>
    
    <x-table striped hover>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>João</td>
                <td><x-badge variant="success">Ativo</x-badge></td>
            </tr>
        </tbody>
    </x-table>
</x-card>
```

### **Documentação Completa**
- 📖 **[Componentes do Sistema](docs/COMPONENTES_SISTEMA.md)** - Guia completo
- 🎨 **Tailwind CSS** - Framework CSS utilitário
- 🔤 **Fonte Lato** - Sistema tipográfico consistente

## 📊 **Estrutura do Projeto**

```
suporte/
├── app/
│   ├── Http/Controllers/     # Controllers organizados
│   │   ├── Admin/           # Controllers administrativos
│   │   └── Auth/            # Controllers de autenticação
│   ├── Models/              # Models Eloquent com relacionamentos
│   ├── Policies/            # Políticas de autorização
│   ├── Services/            # Serviços de negócio
│   ├── Mail/               # Classes Mailable para clientes
│   └── Notifications/       # Notificações para usuários internos
├── database/
│   ├── migrations/          # Migrações do banco de dados
│   └── seeders/            # Seeders para dados iniciais
├── resources/
│   ├── views/              # Templates Blade organizados
│   │   ├── components/     # Componentes Blade reutilizáveis
│   │   ├── admin/          # Views administrativas
│   │   ├── emails/         # Templates de e-mail
│   │   └── layouts/        # Layouts base
│   ├── css/                # Estilos Tailwind CSS
│   └── js/                 # JavaScript modular
├── public/
│   └── fonts/              # Fontes Lato locais
├── docker/                 # Configurações Docker
└── docs/                   # Documentação técnica
```

## 🎯 **Funcionalidades Avançadas**

### **Sistema de Notificações**
- **Notificações Internas**: Para admin/técnicos via Laravel Notifications
- **Notificações para Clientes**: Via Laravel Mailables
- **Templates Personalizáveis**: Editor visual no painel admin
- **Dados Dinâmicos**: Empresa, horários, contatos configuráveis

### **Sistema de Anexos**
- **Preview Inteligente**: PDF, imagens, texto diretamente no navegador
- **Controle de Acesso**: Baseado em roles e permissões
- **Validação Robusta**: Tipos, tamanhos e segurança

### **Soft Delete**
- **Preservação de Dados**: Tickets "excluídos" ficam no banco
- **Recuperação**: Possibilidade de restaurar tickets
- **Auditoria**: Histórico completo preservado

## 🔐 **Segurança**

- **Autenticação**: Laravel Breeze com validação robusta
- **Autorização**: Gates e Policies granulares
- **CSRF Protection**: Tokens em todas as requisições
- **Validação**: Sanitização de dados de entrada
- **Controle de Acesso**: Baseado em roles hierárquicos

## 📈 **Performance**

- **Otimizações de Banco**: Índices e consultas otimizadas
- **Cache**: Configurações e dados frequentes
- **Assets**: Compressão e minificação
- **Docker**: Containers otimizados para produção

## 🧪 **Testes**

### **Testar Notificações**
```bash
# Testar todas as notificações
docker-compose exec app php artisan test:notifications

# Verificar e-mails no Mailpit
# Acesse: http://localhost:8025
```

### **Testar Sistema**
```bash
# Executar testes automatizados
docker-compose exec app php artisan test

# Verificar logs
docker-compose exec app tail -f storage/logs/laravel.log
```

## 🚨 **Troubleshooting**

### **Problemas Comuns**

#### **E-mails não são enviados**
1. Verifique configurações do `.env`
2. Confirme se o Mailpit está rodando (desenvolvimento)
3. Verifique logs: `storage/logs/laravel.log`

#### **Erro 419 (CSRF)**
1. Limpe o cache: `docker-compose exec app php artisan cache:clear`
2. Verifique se o token CSRF está sendo enviado

#### **Problemas de permissão**
1. Verifique se o usuário tem o role correto
2. Confirme as políticas de acesso
3. Verifique logs de autorização

### **Comandos Úteis**
```bash
# Limpar todos os caches
docker-compose exec app php artisan optimize:clear

# Recriar banco de dados
docker-compose exec app php artisan migrate:fresh --seed

# Recompilar assets
docker-compose exec app npm run build

# Verificar status dos containers
docker-compose ps
```

## 🎯 **Roadmap**

### **v1.2 (Próxima versão)**
- [ ] Sistema de SLA (Service Level Agreement)
- [ ] Busca avançada com filtros combinados
- [ ] Templates de resposta personalizáveis
- [ ] Sistema de tags para tickets
- [ ] Relatórios avançados com exportação

### **v1.3 (Futuro)**
- [ ] Notificações push (WebSockets)
- [ ] Integração WhatsApp Business API
- [ ] Sistema de workflows automatizados
- [ ] API REST completa
- [ ] App mobile nativo

## 📝 **Documentação Técnica**

### **Arquitetura**
- **MVC**: Padrão Model-View-Controller
- **Service Layer**: Lógica de negócio centralizada
- **Repository Pattern**: Acesso a dados abstraído
- **Observer Pattern**: Eventos e notificações

### **Banco de Dados**
- **Relacionamentos**: Chaves estrangeiras bem definidas
- **Índices**: Otimização de consultas
- **Soft Deletes**: Preservação de dados
- **Migrations**: Versionamento do schema

### **Frontend**
- **Componentização**: Templates Blade reutilizáveis
- **Responsividade**: Mobile-first design
- **Acessibilidade**: Padrões WCAG 2.1
- **Performance**: Assets otimizados

## 🤝 **Contribuição**

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 **Licença**

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👥 **Desenvolvedor**

**8Bits Pro** - [GitHub](https://github.com/lemsa6)

## 🆕 **Changelog v1.1**

### **Novas Funcionalidades**
- ✅ Sistema de notificações para clientes via Mailable
- ✅ Painel de configurações administrativas
- ✅ Soft delete para tickets
- ✅ Preview de anexos (PDF, imagens, texto)
- ✅ Dados dinâmicos da empresa nos e-mails
- ✅ Links corretos de tickets nas notificações
- ✅ Configuração de e-mail com presets

### **Melhorias**
- ✅ Refatoração do sistema de notificações
- ✅ Templates de e-mail responsivos
- ✅ Interface de configurações intuitiva
- ✅ Validações aprimoradas
- ✅ Performance otimizada

### **Correções**
- ✅ Links de tickets corrigidos nos e-mails
- ✅ Dados da empresa dinâmicos
- ✅ Remoção de links desnecessários
- ✅ Configuração de domínio para produção

## 📋 **Changelog**

### **v1.3 - Sistema de Componentes e Tailwind CSS** (08/09/2025)
- 🆕 **Sistema de Componentes Blade**: 10+ componentes reutilizáveis
- 🆕 **Tailwind CSS**: Framework CSS utilitário moderno
- 🆕 **Fonte Lato**: Sistema tipográfico consistente e local
- 🆕 **Design System**: Classes semânticas para manutenção fácil
- 🆕 **Menu Refatorado**: Uso de componentes x-menu-item
- 🆕 **Dashboard Modernizado**: Interface completamente refatorada
- 🆕 **Documentação Completa**: Guia de componentes e uso
- 🆕 **Vite**: Build tool moderno para assets
- 🆕 **Responsividade**: Mobile-first design aprimorado

### **v1.2 - Sistema de Auditoria Completo** (06/09/2025)
- 🆕 **Sistema de Auditoria**: Rastreamento automático de todas as ações
- 🆕 **Captura de Dados**: IP real, User Agent, data/hora, usuário
- 🆕 **Interface de Consulta**: Lista paginada com filtros avançados
- 🆕 **Estatísticas**: Análise de atividade do sistema
- 🆕 **Exportação CSV**: Dados para análise externa
- 🆕 **Logs por Ticket**: Histórico específico de cada ticket
- 🆕 **Segurança**: Rastreamento de IPs e User Agents
- 🆕 **Documentação**: Documentação técnica completa

### **v1.1 - Sistema de Notificações** (05/09/2025)
- ✅ Sistema de notificações por e-mail
- ✅ Templates personalizáveis
- ✅ Configuração de e-mail com presets
- ✅ Interface de configurações
- ✅ Melhorias na segurança

---

**Sistema de Suporte v1.2** - Desenvolvido com ❤️ em Laravel 12

*Última atualização: 06/09/2025*