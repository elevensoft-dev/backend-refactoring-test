#!/bin/sh
set -e

# Check if .env file exists, if not, copy from .env.example
if [ ! -f ".env" ]; then
    echo "Arquivo .env não encontrado. Copiando de .env.example..."
    cp .env.example .env
fi

# Check if APP_KEY is empty in .env file
if [ ! -f .env ] || ! grep -qE '^APP_KEY=.+' .env; then
    echo "APP_KEY não configurado. Gerando uma nova chave..."
    php artisan key:generate
fi

# Verifica se o diretório vendor existe, se não, instala as dependências
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer (incluindo dependências de desenvolvimento)..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Aguarda o MySQL estar disponível
wait_for_mysql() {
    echo "Aguardando o MySQL iniciar em ${DB_HOST:-mysql}:${DB_PORT:-3306}..."
    until nc -z -w 2 "${DB_HOST:-mysql}" "${DB_PORT:-3306}"; do
        sleep 2
    done
    echo "MySQL está disponível!"
}

# Executa as migrações do banco de dados
run_migrations() {
    echo "Verificando migrações pendentes..."
    php artisan migrate:status | grep -q 'Migration table not found' &&
        php artisan migrate:install

    if php artisan migrate:status | grep -q 'No migrations found'; then
        echo "Nenhuma migração para executar."
    else
        echo "Executando migrações..."
        php artisan migrate --force
    fi
}

# Limpa o cache da aplicação
clear_cache() {
    echo "Limpando cache da aplicação..."
    php artisan config:clear
    php artisan view:clear
    php artisan route:clear
    php artisan cache:clear

    # Otimiza a aplicação (apenas em produção)
    if [ "${APP_ENV}" = "production" ]; then
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    fi
}

# Apenas no primeiro container (usando variável de ambiente)
if [ "${CONTAINER_ROLE:-app}" = "app" ]; then
    wait_for_mysql
    clear_cache
    run_migrations
fi

# Inicia o PHP-FPM em primeiro plano
echo "Iniciando PHP-FPM..."
# Usa o caminho completo para o php-fpm
exec php-fpm
