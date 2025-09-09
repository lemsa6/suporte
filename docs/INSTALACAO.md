# 🚀 Guia de Instalação - Sistema de Suporte v1.2

> **📚 Para documentação completa, consulte o [Compêndio do Sistema](COMPENDIO_SISTEMA_SUPORTE.md)**

## **Pré-requisitos**

### **Sistema Operacional**
- **Windows 10/11** (recomendado)
- **macOS 10.15+**
- **Linux Ubuntu 20.04+**

### **Software Necessário**
- **Docker Desktop** 4.0+
- **Git** 2.30+
- **Navegador moderno** (Chrome, Firefox, Safari, Edge)

## **📋 Instalação Passo a Passo**

### **1. Preparação do Ambiente**

#### **Windows**
1. Baixe e instale o [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. Baixe e instale o [Git for Windows](https://git-scm.com/download/win)
3. Reinicie o computador após as instalações

#### **macOS**
```bash
# Instalar Homebrew (se não tiver)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Instalar Docker Desktop
brew install --cask docker

# Instalar Git
brew install git
```

#### **Linux (Ubuntu)**
```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Instalar Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Instalar Git
sudo apt install git -y
```

### **2. Clone do Repositório**

```bash
# Clone o repositório
git clone https://github.com/lemsa6/suporte.git

# Entre na pasta do projeto
cd suporte

# Verifique se está na pasta correta
ls -la
```

### **3. Configuração do Ambiente**

#### **Criar arquivo .env**
```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Edite o arquivo .env com suas configurações
nano .env  # ou use seu editor preferido
```

#### **Configurações básicas do .env**
```env
# Configurações da aplicação
APP_NAME="Sistema de Suporte"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:9000

# Configurações do banco de dados
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=suporte
DB_USERNAME=root
DB_PASSWORD=root

# Configurações de e-mail (desenvolvimento)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="contato@8bits.pro"
MAIL_FROM_NAME="Sistema de Suporte"

# Configurações de cache
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### **4. Inicialização com Docker**

#### **Iniciar os containers**
```bash
# Iniciar todos os serviços
docker-compose up -d

# Verificar se os containers estão rodando
docker-compose ps
```

**Containers esperados:**
- `suporte-app` (Laravel/PHP)
- `suporte-nginx` (Web server)
- `suporte-mysql` (Banco de dados)
- `suporte-redis` (Cache)
- `suporte-mailpit` (E-mail para desenvolvimento)

#### **Verificar logs**
```bash
# Ver logs de todos os containers
docker-compose logs

# Ver logs de um container específico
docker-compose logs app
docker-compose logs mysql
```

### **5. Configuração da Aplicação**

#### **Instalar dependências**
```bash
# Instalar dependências PHP
docker-compose exec app composer install

# Instalar dependências Node.js
docker-compose exec app npm install
```

#### **Configurar banco de dados**
```bash
# Gerar chave da aplicação
docker-compose exec app php artisan key:generate

# Executar migrações
docker-compose exec app php artisan migrate

# Popular banco com dados iniciais
docker-compose exec app php artisan db:seed
```

#### **Compilar assets**
```bash
# Compilar CSS e JavaScript
docker-compose exec app npm run build
```

### **6. Verificação da Instalação**

#### **Acessar o sistema**
- **URL**: http://localhost:9000
- **Login padrão**: admin@admin.com
- **Senha**: password

#### **Verificar e-mails (desenvolvimento)**
- **Mailpit**: http://localhost:8025
- Todas as notificações aparecerão aqui

#### **Testar funcionalidades**
1. **Login** com usuário admin
2. **Criar um cliente** novo
3. **Criar um ticket** para o cliente
4. **Verificar notificações** no Mailpit

## **🔧 Configurações Avançadas**

### **1. Configuração de E-mail para Produção**

#### **Gmail (Recomendado)**
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

**Como obter chave de aplicativo do Gmail:**
1. Acesse [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
2. Faça login com sua conta Google
3. Selecione "App" e "Other"
4. Digite "Sistema de Suporte"
5. Copie a chave gerada (16 caracteres)
6. Use esta chave no campo `MAIL_PASSWORD`

#### **Outlook/Hotmail**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@outlook.com
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
```

#### **SendGrid**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=sua-api-key-sendgrid
MAIL_ENCRYPTION=tls
```

### **2. Configuração de Banco de Dados Externa**

```env
# Para usar banco externo (MySQL/PostgreSQL)
DB_CONNECTION=mysql
DB_HOST=seu-host-externo
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha_segura
```

### **3. Configuração de Cache (Redis)**

```env
# Para usar Redis como cache
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Configurações do Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## **🚨 Troubleshooting**

### **Problemas Comuns**

#### **1. Erro "Port already in use"**
```bash
# Verificar portas em uso
netstat -tulpn | grep :9000

# Parar containers
docker-compose down

# Iniciar novamente
docker-compose up -d
```

#### **2. Erro de permissão no Docker**
```bash
# Linux/macOS - Adicionar usuário ao grupo docker
sudo usermod -aG docker $USER

# Logout e login novamente
```

#### **3. Erro "Database connection failed"**
```bash
# Verificar se MySQL está rodando
docker-compose logs mysql

# Aguardar MySQL inicializar
docker-compose exec app php artisan migrate:status
```

#### **4. Erro "Class not found"**
```bash
# Limpar cache
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

# Reinstalar dependências
docker-compose exec app composer install --no-cache
```

#### **5. Assets não carregam**
```bash
# Recompilar assets
docker-compose exec app npm run build

# Verificar permissões
docker-compose exec app chmod -R 755 public/
```

### **Comandos de Diagnóstico**

```bash
# Verificar status dos containers
docker-compose ps

# Ver logs em tempo real
docker-compose logs -f

# Acessar container da aplicação
docker-compose exec app bash

# Verificar configurações
docker-compose exec app php artisan config:show

# Testar conexão com banco
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

## **📊 Verificação Pós-Instalação**

### **1. Checklist de Funcionalidades**

- [ ] **Login** funciona com usuário admin
- [ ] **Dashboard** carrega com estatísticas
- [ ] **Criar cliente** funciona
- [ ] **Criar ticket** funciona
- [ ] **Notificações** são enviadas (verificar Mailpit)
- [ ] **Upload de anexos** funciona
- [ ] **Preview de anexos** funciona
- [ ] **Soft delete** de tickets funciona
- [ ] **Painel de configurações** acessível (admin)

### **2. Teste de Notificações**

```bash
# Testar todas as notificações
docker-compose exec app php artisan test:notifications

# Verificar no Mailpit: http://localhost:8025
```

### **3. Teste de Performance**

```bash
# Verificar tempo de resposta
curl -w "@curl-format.txt" -o /dev/null -s http://localhost:9000

# Verificar uso de memória
docker stats
```

## **🔄 Atualizações**

### **1. Atualizar Código**
```bash
# Parar containers
docker-compose down

# Atualizar código
git pull origin main

# Reconstruir containers
docker-compose up -d --build

# Atualizar banco de dados
docker-compose exec app php artisan migrate

# Recompilar assets
docker-compose exec app npm run build
```

### **2. Backup do Banco**
```bash
# Fazer backup
docker-compose exec mysql mysqldump -u root -proot suporte > backup.sql

# Restaurar backup
docker-compose exec -T mysql mysql -u root -proot suporte < backup.sql
```

## **🚀 Deploy em Produção**

### **1. Preparação**
```bash
# Configurar .env para produção
cp .env .env.production

# Editar configurações de produção
nano .env.production
```

### **2. Configurações de Produção**
```env
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

### **3. Otimizações**
```bash
# Otimizar para produção
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan optimize
```

## **📞 Suporte**

### **Problemas de Instalação**
- **GitHub Issues**: [Criar issue](https://github.com/lemsa6/suporte/issues)
- **E-mail**: contato@8bits.pro
- **Documentação**: Este arquivo

### **Comandos Úteis**
```bash
# Reiniciar tudo
docker-compose down && docker-compose up -d

# Ver logs de erro
docker-compose logs app | grep ERROR

# Limpar tudo e reinstalar
docker-compose down -v
docker-compose up -d --build
```

---

**Guia de Instalação v1.1** - Sistema de Suporte

*Última atualização: 05/09/2025*
