# 🚀 Instalação Rápida - Sistema de Suporte v1.2

> **📚 Para instalação detalhada, consulte [docs/INSTALACAO.md](docs/INSTALACAO.md)**

## **Pré-requisitos**

- Docker e Docker Compose
- Git
- Navegador moderno

## **Instalação em 5 Passos**

### **1. Clone o repositório**
```bash
git clone https://github.com/lemsa6/suporte.git
cd suporte
```

### **2. Configure o ambiente**
```bash
cp .env.example .env
# Edite o .env com suas configurações
```

### **3. Execute com Docker**
```bash
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app npm install
```

### **4. Configure o banco**
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app npm run build
```

### **5. Acesse o sistema**
- **URL**: http://localhost:9000
- **Login**: admin@admin.com / password
- **E-mails**: http://localhost:8025 (Mailpit)

## **Configuração de E-mail**

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

**Importante:** Use uma [Chave de Aplicativo](https://myaccount.google.com/apppasswords) do Gmail.

## **Troubleshooting Rápido**

### **Problemas Comuns**
```bash
# E-mails não chegam
docker-compose logs mailpit

# Erro 419 (CSRF)
docker-compose exec app php artisan cache:clear

# Assets não carregam
docker-compose exec app npm run build

# Banco não conecta
docker-compose logs mysql
```

### **Comandos Úteis**
```bash
# Reiniciar tudo
docker-compose down && docker-compose up -d

# Ver logs
docker-compose logs -f

# Limpar cache
docker-compose exec app php artisan optimize:clear
```

## **Documentação Completa**

- **[Guia de Instalação Detalhado](docs/INSTALACAO.md)** - Instalação passo a passo
- **[Guia de Uso](docs/USO_SISTEMA.md)** - Como usar o sistema
- **[Arquitetura](docs/ARQUITETURA.md)** - Documentação técnica
- **[README Principal](README.md)** - Visão geral do projeto

## **Suporte**

- **GitHub Issues**: [Criar issue](https://github.com/lemsa6/suporte/issues)
- **E-mail**: contato@8bits.pro
- **Documentação**: Pasta `docs/`

---

**Sistema de Suporte v1.1** - Instalação Rápida

*Para instalação detalhada, consulte [docs/INSTALACAO.md](docs/INSTALACAO.md)*