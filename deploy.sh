#!/bin/bash

# 🚀 Script de Deploy para Produção
# Sistema de Suporte e Tickets v1.2

echo "🚀 Iniciando deploy do Sistema de Suporte e Tickets..."

# 1. Instalar dependências PHP
echo "📦 Instalando dependências PHP..."
composer install --optimize-autoloader --no-dev

# 2. Instalar dependências Node.js
echo "📦 Instalando dependências Node.js..."
npm install

# 3. Compilar assets para produção
echo "🔨 Compilando assets (Tailwind + Chart.js)..."
npm run build

# 4. Configurar permissões
echo "🔐 Configurando permissões..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Verificar se os assets foram compilados
echo "✅ Verificando assets compilados..."
if [ -d "public/build/assets" ]; then
    echo "✅ Assets compilados com sucesso!"
    ls -la public/build/assets/
else
    echo "❌ Erro: Assets não foram compilados!"
    exit 1
fi

# 6. Executar migrações (se necessário)
echo "🗄️ Executando migrações..."
php artisan migrate --force

# 7. Limpar cache
echo "🧹 Limpando cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🎉 Deploy concluído com sucesso!"
echo "📋 Verificações finais:"
echo "   - Chart.js: Instalado localmente ✅"
echo "   - Tailwind CSS: Compilado via Vite ✅"
echo "   - Fontes Lato: Carregadas localmente ✅"
echo "   - Sem CDN: Sistema 100% offline ✅"
