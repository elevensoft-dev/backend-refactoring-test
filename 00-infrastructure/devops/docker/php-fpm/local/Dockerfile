FROM php:8.3-fpm-alpine3.22 AS eleven-php-fpm83-builder

RUN apk update && apk add --no-cache \
    linux-headers bash shadow libaio curl git icu-dev libnsl \
    unzip zip supervisor tzdata ca-certificates \
    # Pacotes para compilação
    $PHPIZE_DEPS pkgconfig \
    # Dependências para GD
    libpng libpng-dev libjpeg-turbo-dev libjpeg-turbo \
    libwebp-dev libwebp freetype freetype-dev libxpm-dev \
    # Outras dependências
    libxml2-dev libzip-dev zlib-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql zip gd opcache pcntl intl bcmath sockets \
    # PECL extensões
    && pecl install xdebug excimer redis \
    && docker-php-ext-enable xdebug excimer redis opcache \
    # Limpar cache
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Instalação do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN rm -rf /var/www/html \
    && ln -s /var/www/public /var/www/html \
    && chown -R www-data:www-data /var/www

##### LOCAL DEVELOPMENT
FROM eleven-php-fpm83-builder

WORKDIR /var/www

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    chown -R www-data:www-data /var/www && \
    usermod -u 1000 www-data

# Configura o PHP-FPM
COPY confs/www.conf /usr/local/etc/php-fpm.d/zz-custom.conf

# Cria diretório para o entrypoint e garante permissões
RUN mkdir -p /docker-entrypoint.d && \
    chmod 777 /docker-entrypoint.d

# Expõe a porta do PHP-FPM
EXPOSE 9000

# Define o entrypoint que será montado como volume
#ENTRYPOINT ["/docker-entrypoint.d/00-app-init.sh"]
ENTRYPOINT ["sh", "-c", "chmod +x /etc/entrypoint.sh && /etc/entrypoint.sh"]
