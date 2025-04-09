# Dockerfile para o ambiente de desenvolvimento do Laravel 12
FROM debian:12-slim

# Informações básicas sobre o container
LABEL maintainer="83130766+CaioSimioni@users.noreply.github.com"
LABEL version="1.0"
LABEL description="Container de desenvolvimento para projetos Laravel 12 com PHP 8.3, Node.js, Composer e Yarn."

# Labels
ENV TZ=UTC \
    DEBIAN_FRONTEND=noninteractive \
    LANG=C.UTF-8 \
    LC_ALL=C.UTF-8 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="/var/www/vendor/bin:$PATH"

# Atualiza os repositórios e instala dependências básicas
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    mariadb-client \
    ca-certificates \
    lsb-release \
    gnupg2

# Adiciona o repositório do PHP
RUN wget -qO - https://packages.sury.org/php/apt.gpg | gpg --dearmor > /etc/apt/trusted.gpg.d/php.gpg && \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

# Atualiza os repositórios novamente e instala o PHP e extensões necessárias
RUN apt-get update && apt-get install -y \
    php8.3 \
    php8.3-cli \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-bcmath \
    php8.3-curl \
    php8.3-zip \
    php8.3-sqlite3 \
    php8.3-mysql \
    php8.3-gd \
    php8.3-pdo \
    php8.3-tokenizer

# Limpa os arquivos temporários do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Node.js + yarn + vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g yarn vite

# Workdir
WORKDIR /var/www

# Copia o código
COPY . .

# Configurações do ambiente
RUN cp .env.example .env

# Criação de diretórios necessários
RUN mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache

# Ajuste de permissões
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instala dependências JS
RUN yarn install

# Realiza o build dos assets
RUN yarn build

# Configurações iniciais do banco de dados
RUN rm -f database/database.sqlite && \
    touch database/database.sqlite

# Configurações do Laravel
RUN php artisan key:generate && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Executa migrations e seeds
RUN php artisan migrate --force && \
    php artisan db:seed --force

# Expor a porta da aplicação
EXPOSE 8080

# Usa a porta do Heroku
ENV PORT=8080

# Comando para iniciar o servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
