#!/bin/bash

# 🎫 Sistema de Suporte e Tickets - Script de Instalação
# Versão: 1.0
# Compatível com: Debian 12, Ubuntu 22.04+

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para imprimir mensagens coloridas
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_header() {
    echo -e "${BLUE}================================${NC}"
    echo -e "${BLUE}  Sistema de Suporte v1.0${NC}"
    echo -e "${BLUE}  Instalação Automática${NC}"
    echo -e "${BLUE}================================${NC}"
    echo
}

# Verificar se é root
check_root() {
    if [[ $EUID -eq 0 ]]; then
        print_warning "Executando como root. Continuando..."
    else
        print_message "Executando como usuário normal."
    fi
}

# Verificar sistema operacional
check_os() {
    if [[ -f /etc/os-release ]]; then
        . /etc/os-release
        OS=$NAME
        VER=$VERSION_ID
    else
        print_error "Sistema operacional não suportado!"
        exit 1
    fi

    print_message "Sistema detectado: $OS $VER"
    
    if [[ "$OS" != "Debian GNU/Linux" && "$OS" != "Ubuntu" ]]; then
        print_warning "Sistema não testado. Continuando mesmo assim..."
    fi
}

# Atualizar sistema
update_system() {
    print_message "Atualizando sistema..."
    apt update && apt upgrade -y
    apt install -y curl wget git nano htop unzip software-properties-common
}

# Instalar PHP 8.2
install_php() {
    print_message "Instalando PHP 8.2..."
    
    # Adicionar repositório PHP
    add-apt-repository ppa:ondrej/php -y
    apt update
    
    # Instalar PHP e extensões
    apt install -y php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring \
        php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-readline \
        php8.2-xmlrpc php8.2-soap php8.2-ldap php8.2-imagick
    
    # Configurar PHP
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 100M/' /etc/php/8.2/fpm/php.ini
    sed -i 's/post_max_size = 8M/post_max_size = 100M/' /etc/php/8.2/fpm/php.ini
    sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/8.2/fpm/php.ini
    sed -i 's/memory_limit = 128M/memory_limit = 512M/' /etc/php/8.2/fpm/php.ini
    
    print_message "PHP 8.2 instalado com sucesso!"
}

# Instalar MySQL
install_mysql() {
    print_message "Instalando MySQL 8.0..."
    
    apt install -y mysql-server mysql-client
    
    # Configurar MySQL
    mysql_secure_installation
    
    print_message "MySQL instalado com sucesso!"
}

# Instalar Nginx
install_nginx() {
    print_message "Instalando Nginx..."
    
    apt install -y nginx
    
    # Configurar Nginx
    systemctl enable nginx
    systemctl start nginx
    
    print_message "Nginx instalado com sucesso!"
}

# Instalar Node.js
install_nodejs() {
    print_message "Instalando Node.js 18..."
    
    curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
    apt install -y nodejs
    
    print_message "Node.js instalado com sucesso!"
}

# Instalar Composer
install_composer() {
    print_message "Instalando Composer..."
    
    cd /tmp
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    
    print_message "Composer instalado com sucesso!"
}

# Configurar banco de dados
setup_database() {
    print_message "Configurando banco de dados..."
    
    # Ler configurações do .env
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
    DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
    
    # Criar banco e usuário
    mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS $DB_DATABASE;"
    mysql -u root -p -e "CREATE USER IF NOT EXISTS '$DB_USERNAME'@'localhost' IDENTIFIED BY '$DB_PASSWORD';"
    mysql -u root -p -e "GRANT ALL PRIVILEGES ON $DB_DATABASE.* TO '$DB_USERNAME'@'localhost';"
    mysql -u root -p -e "FLUSH PRIVILEGES;"
    
    print_message "Banco de dados configurado com sucesso!"
}

# Configurar Nginx
setup_nginx() {
    print_message "Configurando Nginx..."
    
    # Criar configuração do site
    tee /etc/nginx/sites-available/suporte > /dev/null <<EOF
server {
    listen 80;
    server_name _;
    root $(pwd)/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

    # Ativar site
    ln -sf /etc/nginx/sites-available/suporte /etc/nginx/sites-enabled/
    rm -f /etc/nginx/sites-enabled/default
    
    # Testar configuração
    nginx -t
    
    # Reiniciar Nginx
    systemctl restart nginx
    
    print_message "Nginx configurado com sucesso!"
}

# Instalar dependências do projeto
install_dependencies() {
    print_message "Instalando dependências do projeto..."
    
    # Instalar dependências PHP
    composer install --optimize-autoloader --no-dev
    
    # Instalar dependências Node.js
    npm install
    
    # Compilar assets
    npm run build
    
    print_message "Dependências instaladas com sucesso!"
}

# Configurar Laravel
setup_laravel() {
    print_message "Configurando Laravel..."
    
    # Gerar chave da aplicação
    php artisan key:generate
    
    # Configurar permissões
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R 775 storage bootstrap/cache
    
    # Criar link simbólico para storage
    php artisan storage:link
    
    # Executar migrações
    php artisan migrate --force
    
    # Executar seeders
    php artisan db:seed --force
    
    # Limpar cache
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    print_message "Laravel configurado com sucesso!"
}

# Configurar SSL (Let's Encrypt)
setup_ssl() {
    if [[ "$1" == "--ssl" ]]; then
        print_message "Configurando SSL com Let's Encrypt..."
        
        # Instalar Certbot
        apt install -y certbot python3-certbot-nginx
        
        # Obter certificado
        read -p "Digite o domínio para SSL: " DOMAIN
        certbot --nginx -d $DOMAIN
        
        print_message "SSL configurado com sucesso!"
    fi
}

# Configurar firewall
setup_firewall() {
    print_message "Configurando firewall..."
    
    ufw allow 22/tcp
    ufw allow 80/tcp
    ufw allow 443/tcp
    ufw --force enable
    
    print_message "Firewall configurado com sucesso!"
}

# Criar script de backup
create_backup_script() {
    print_message "Criando script de backup..."
    
    tee /usr/local/bin/backup-suporte.sh > /dev/null <<EOF
#!/bin/bash
DATE=\$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/suporte"
mkdir -p \$BACKUP_DIR

# Backup do banco
DB_DATABASE=\$(grep DB_DATABASE $(pwd)/.env | cut -d '=' -f2)
DB_USERNAME=\$(grep DB_USERNAME $(pwd)/.env | cut -d '=' -f2)
DB_PASSWORD=\$(grep DB_PASSWORD $(pwd)/.env | cut -d '=' -f2)

mysqldump -u \$DB_USERNAME -p\$DB_PASSWORD \$DB_DATABASE > \$BACKUP_DIR/database_\$DATE.sql

# Backup dos arquivos
tar -czf \$BACKUP_DIR/files_\$DATE.tar.gz $(pwd)

# Manter apenas os últimos 7 backups
find \$BACKUP_DIR -name "*.sql" -mtime +7 -delete
find \$BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup realizado: \$DATE"
EOF

    chmod +x /usr/local/bin/backup-suporte.sh
    
    # Configurar crontab
    (crontab -l 2>/dev/null; echo "0 2 * * * /usr/local/bin/backup-suporte.sh") | crontab -
    
    print_message "Script de backup criado com sucesso!"
}

# Função principal
main() {
    print_header
    
    # Verificações iniciais
    check_root
    check_os
    
    # Atualizar sistema
    update_system
    
    # Instalar dependências
    install_php
    install_mysql
    install_nginx
    install_nodejs
    install_composer
    
    # Configurar banco de dados
    setup_database
    
    # Instalar dependências do projeto
    install_dependencies
    
    # Configurar Laravel
    setup_laravel
    
    # Configurar Nginx
    setup_nginx
    
    # Configurar SSL (opcional)
    setup_ssl "$@"
    
    # Configurar firewall
    setup_firewall
    
    # Criar script de backup
    create_backup_script
    
    print_message "Instalação concluída com sucesso!"
    print_message "Acesse: http://$(hostname -I | awk '{print $1}')"
    print_message "Login: admin@admin.com"
    print_message "Senha: password"
    
    if [[ "$1" == "--ssl" ]]; then
        print_message "Acesse: https://$DOMAIN"
    fi
}

# Executar função principal
main "$@"