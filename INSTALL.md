# 🚀 Instalação do Sistema de Suporte

## 📋 Pré-requisitos

- **Sistema Operacional**: Debian 12, Ubuntu 22.04+ ou similar
- **Acesso**: Usuário com privilégios sudo
- **Memória**: Mínimo 2GB RAM
- **Disco**: Mínimo 10GB espaço livre
- **Rede**: Acesso à internet

## ⚡ Instalação Rápida

### 1. Clone o repositório
```bash
git clone https://github.com/lemsa6/suporte.git
cd suporte
```

### 2. Configure o ambiente
```bash
# Copie o arquivo de configuração
cp .env.example .env

# Edite as configurações (opcional)
nano .env
```

### 3. Execute a instalação
```bash
# Torne o script executável
chmod +x install.sh

# Execute a instalação
sudo ./install.sh
```

### 4. Acesse o sistema
- **URL**: `http://seu-ip` ou `http://seu-dominio`
- **Login**: `admin@admin.com`
- **Senha**: `password`

## 🔧 Instalação com SSL

Para instalar com certificado SSL automático:

```bash
sudo ./install.sh --ssl
```

O script irá solicitar o domínio e configurar automaticamente o Let's Encrypt.

## 📝 Configurações Importantes

### Banco de Dados
Edite o arquivo `.env` para configurar o banco:

```env
DB_DATABASE=suporte
DB_USERNAME=suporte
DB_PASSWORD=sua_senha_forte
```

### E-mail
Configure o envio de e-mails:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-de-app
MAIL_ENCRYPTION=tls
```

## 🛠 O que o script instala

- **PHP 8.2** com todas as extensões necessárias
- **MySQL 8.0** configurado e seguro
- **Nginx** com configuração otimizada
- **Node.js 18** para compilação de assets
- **Composer** para dependências PHP
- **Certbot** para SSL (opcional)
- **Firewall UFW** configurado
- **Script de backup** automático

## 🔄 Pós-instalação

### 1. Configurar domínio (opcional)
```bash
# Edite a configuração do Nginx
sudo nano /etc/nginx/sites-available/suporte

# Altere o server_name
server_name seu-dominio.com;

# Reinicie o Nginx
sudo systemctl restart nginx
```

### 2. Configurar SSL manualmente
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Obter certificado
sudo certbot --nginx -d seu-dominio.com
```

### 3. Configurar backup
O script já cria um backup automático, mas você pode ajustar:

```bash
# Editar configurações de backup
sudo nano /usr/local/bin/backup-suporte.sh

# Verificar crontab
crontab -l
```

## 🚨 Solução de Problemas

### Erro de permissões
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Erro de banco de dados
```bash
# Verificar status do MySQL
sudo systemctl status mysql

# Reiniciar MySQL
sudo systemctl restart mysql
```

### Erro de Nginx
```bash
# Testar configuração
sudo nginx -t

# Ver logs
sudo tail -f /var/log/nginx/error.log
```

### Erro de PHP
```bash
# Verificar status do PHP-FPM
sudo systemctl status php8.2-fpm

# Reiniciar PHP-FPM
sudo systemctl restart php8.2-fpm
```

## 📊 Monitoramento

### Verificar status dos serviços
```bash
# Status geral
sudo systemctl status nginx mysql php8.2-fpm

# Logs em tempo real
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/mysql/error.log
```

### Verificar uso de recursos
```bash
# Uso de memória e CPU
htop

# Uso de disco
df -h

# Uso de banco de dados
mysql -u root -p -e "SHOW PROCESSLIST;"
```

## 🔄 Atualizações

### Atualizar o sistema
```bash
# Atualizar código
git pull origin main

# Atualizar dependências
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Limpar cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Atualizar banco de dados
```bash
# Executar migrações
php artisan migrate --force
```

## 📞 Suporte

Se encontrar problemas:

1. Verifique os logs de erro
2. Consulte a documentação
3. Abra uma issue no GitHub
4. Entre em contato com o desenvolvedor

---

**Sistema de Suporte v1.0** - Instalação automatizada 🚀
