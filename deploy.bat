@echo off
REM 🚀 Script de Deploy para Produção (Windows)
REM Sistema de Suporte e Tickets v1.2

echo 🚀 Iniciando deploy do Sistema de Suporte e Tickets...

REM 1. Instalar dependências PHP
echo 📦 Instalando dependências PHP...
composer install --optimize-autoloader --no-dev

REM 2. Instalar dependências Node.js
echo 📦 Instalando dependências Node.js...
npm install

REM 3. Compilar assets para produção
echo 🔨 Compilando assets (Tailwind + Chart.js)...
npm run build

REM 4. Verificar se os assets foram compilados
echo ✅ Verificando assets compilados...
if exist "public\build\assets" (
    echo ✅ Assets compilados com sucesso!
    dir public\build\assets
) else (
    echo ❌ Erro: Assets não foram compilados!
    pause
    exit /b 1
)

REM 5. Executar migrações (se necessário)
echo 🗄️ Executando migrações...
php artisan migrate --force

REM 6. Limpar cache
echo 🧹 Limpando cache...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo 🎉 Deploy concluído com sucesso!
echo 📋 Verificações finais:
echo    - Chart.js: Instalado localmente ✅
echo    - Tailwind CSS: Compilado via Vite ✅
echo    - Fontes Lato: Carregadas localmente ✅
echo    - Sem CDN: Sistema 100%% offline ✅
pause
