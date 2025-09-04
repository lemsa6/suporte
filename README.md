# 🎫 Sistema de Suporte e Tickets - v1.0

Sistema completo de gerenciamento de tickets de suporte desenvolvido em Laravel 12, com interface responsiva e funcionalidades avançadas para empresas e clientes.

## 🚀 **Funcionalidades Principais**

### ✅ **Sistema de Autenticação e Autorização**
- Login/Logout com Laravel Breeze
- Controle de acesso por roles (admin, tecnico, cliente)
- Gates e Policies implementados
- Middleware de autorização

### ✅ **Gestão de Clientes e Contatos**
- CRUD completo de empresas
- Validação de CNPJ
- Sistema de contatos por cliente
- Contato principal (único por cliente)
- Interface AJAX para edição

### ✅ **Sistema de Tickets**
- CRUD completo de tickets
- Sistema de status (aberto, em andamento, resolvido, fechado)
- Sistema de prioridade (baixa, média, alta)
- Atribuição de responsáveis
- Histórico de mensagens
- Sistema de anexos/upload

### ✅ **Sistema de Notificações**
- 6 tipos de notificações por e-mail
- Serviço centralizado de notificações
- Configuração Gmail SMTP
- Notificações para admin/técnicos

### ✅ **Sistema de Roles Hierárquicos**
- **Role `cliente_gestor`**: Pode ver todos os tickets da empresa
- **Role `cliente_funcionario`**: Pode ver apenas seus próprios tickets
- Dashboard adaptativo baseado no role
- Políticas de acesso granulares

### ✅ **Dashboard e Relatórios**
- Dashboard principal com estatísticas
- Relatórios de tickets e clientes
- Gráficos e métricas
- Interface responsiva para mobile

## 🛠 **Tecnologias Utilizadas**

### **Backend**
- **Laravel 12** - Framework PHP
- **PHP 8.2+** - Linguagem de programação
- **MySQL 8.0** - Banco de dados
- **Docker** - Containerização

### **Frontend**
- **Bootstrap 5** - Framework CSS
- **SCSS** - Pré-processador CSS
- **JavaScript Vanilla** - Interatividade
- **AJAX** - Requisições assíncronas

### **Infraestrutura**
- **Docker Compose** - Orquestração de containers
- **Nginx** - Web server
- **Mailpit** - Servidor de e-mail para desenvolvimento
- **Gmail SMTP** - Servidor de e-mail para produção

## 📋 **Pré-requisitos**

- Docker e Docker Compose
- Git
- Navegador moderno

## 🚀 **Instalação**

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

## 📱 **Responsividade**

O sistema é totalmente responsivo e funciona perfeitamente em:
- 📱 **Mobile** (320px+)
- 📱 **Tablet** (768px+)
- 💻 **Desktop** (1024px+)

## 🔧 **Configuração de E-mail**

Para configurar o envio de e-mails, edite o arquivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-de-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Sistema de Suporte"
```

## 📊 **Estrutura do Projeto**

```
suporte/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/              # Models Eloquent
│   ├── Policies/            # Políticas de autorização
│   ├── Services/            # Serviços de negócio
│   └── Notifications/       # Notificações por e-mail
├── database/
│   ├── migrations/          # Migrações do banco
│   └── seeders/            # Seeders para dados iniciais
├── resources/
│   ├── views/              # Templates Blade
│   ├── scss/               # Estilos SCSS
│   └── js/                 # JavaScript
├── docker/                 # Configurações Docker
└── docs/                   # Documentação
```

## 🎯 **Próximas Funcionalidades**

- [ ] Sistema de SLA (Service Level Agreement)
- [ ] Busca avançada com filtros
- [ ] Templates de resposta
- [ ] Sistema de tags
- [ ] Notificações push
- [ ] Relatórios avançados
- [ ] Integração WhatsApp

## 📝 **Documentação**

Consulte a pasta `docs/` para documentação detalhada:
- `PROGRESSO.md` - Status do desenvolvimento
- `configuracoes-sistema.md` - Configurações do sistema
- `SISTEMA_NOTIFICACOES.md` - Sistema de notificações
- `integracao-oauth-whatsapp.md` - Integração WhatsApp

## 🤝 **Contribuição**

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 **Licença**

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👥 **Desenvolvedor**

**Lemsa6** - [GitHub](https://github.com/lemsa6)

## 🆕 **Changelog v1.0**

### **Funcionalidades Implementadas**
- ✅ Sistema completo de tickets
- ✅ Gestão de clientes e contatos
- ✅ Sistema de notificações por e-mail
- ✅ Roles hierárquicos para clientes
- ✅ Dashboard responsivo
- ✅ Sistema de anexos
- ✅ Interface mobile-first

### **Melhorias de Performance**
- ✅ Otimizações de banco de dados
- ✅ Cache de configurações
- ✅ Compressão de assets
- ✅ Otimizações de consultas

---

**Sistema de Suporte v1.0** - Desenvolvido com ❤️ em Laravel 12