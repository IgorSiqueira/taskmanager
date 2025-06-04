# Utiliza a imagem oficial do PHP 8.2-FPM Alpine como base
FROM php:8.2-fpm-alpine

# Define o diretório de trabalho padrão dentro do contêiner
WORKDIR /var/www/html

# Instala dependências do sistema necessárias para as extensões PHP e outras ferramentas (usando apk)
# --no-cache é usado para não manter o cache do índice de pacotes, reduzindo o tamanho da imagem
RUN apk update && apk add --no-cache \
    # Alpine usa ash por padrão, bash pode ser útil para scripts
    bash \
    git \
    curl \
    unzip \
    zip \
    # Dependências para extensões PHP:
    freetype-dev \
    # Geralmente preferido em vez de jpeg-dev
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    # Para a extensão intl. Para todos os locales, você pode precisar de icu-data-full
    icu-dev \
    # Para a extensão mbstring
    oniguruma-dev \
    # Para pdo_pgsql, caso precise no futuro (opcional)
    # postgresql-dev \
    # Adicione outras dependências de sistema se necessário aqui, antes do '&& rm -rf'
    && rm -rf /var/cache/apk/* # Limpa o cache do apk

# Configura e instala as extensões PHP
# O -j$(nproc) usa todos os cores disponíveis para acelerar a compilação das extensões
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install exif \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install bcmath

# Instala o Composer globalmente na imagem
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Opcional: Copia configurações personalizadas do PHP
# Se você criar um arquivo 'custom-php.ini' em 'docker/php/', descomente a linha abaixo.
# COPY ./docker/php/custom-php.ini /usr/local/etc/php/conf.d/zz-custom-php.ini

# A imagem base php:8.2-fpm-alpine já expõe a porta 9000 e define o CMD para iniciar o php-fpm.