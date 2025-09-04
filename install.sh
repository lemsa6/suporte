#!/bin/bash

echo "🚀 Sistema de Tickets - Instalação Automatizada"
echo "================================================"

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verificar se Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

echo "✅ Docker e Docker Compose encontrados"

# Criar arquivo .env se não existir
if [ ! -f .env ]; then
    echo "📝 Criando arquivo .env..."
    cp .env.example .env
    echo "✅ Arquivo .env criado"
else
    echo "✅ Arquivo .env já existe"
fi

# Parar containers existentes
echo "🛑 Parando containers existentes..."
docker-compose down

# Construir e iniciar containers
echo "🔨 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguardar containers estarem prontos
echo "⏳ Aguardando containers estarem prontos..."
sleep 30

# Verificar se containers estão rodando
if ! docker-compose ps | grep -q "Up"; then
    echo "❌ Erro ao iniciar containers. Verifique os logs:"
    docker-compose logs
    exit 1
fi

echo "✅ Containers iniciados com sucesso"

# Instalar dependências do Laravel
echo "📦 Instalando dependências do Laravel..."
docker-compose exec -T app composer install --no-interaction

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker-compose exec -T app php artisan key:generate

# Criar link simbólico para storage
echo "🔗 Criando link simbólico para storage..."
docker-compose exec -T app php artisan storage:link

# Executar migrations
echo "🗄️ Executando migrations..."
docker-compose exec -T app php artisan migrate --force

# Executar seeders
echo "🌱 Executando seeders..."
docker-compose exec -T app php artisan db:seed --force

# Limpar caches
echo "🧹 Limpando caches..."
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan cache:clear
docker-compose exec -T app php artisan view:clear

echo ""
echo "🎉 Instalação concluída com sucesso!"
echo ""
echo "📱 Acesse o sistema em: http://localhost:9000"
echo ""
echo "👤 Credenciais de acesso:"
echo "   Admin: admin@exemplo.com / password"
echo "   Técnico: tecnico@exemplo.com / password"
echo ""
echo "🔧 Comandos úteis:"
echo "   - Ver logs: docker-compose logs -f"
echo "   - Parar: docker-compose down"
echo "   - Reiniciar: docker-compose restart"
echo ""
echo "📚 Para mais informações, consulte o README.md"
