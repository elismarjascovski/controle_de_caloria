FROM php:8.2-fpm

# Instalar dependências do sistema e extensões do PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip

# Limpar cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões necessárias para o projeto (PDO MySQL)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copiar o Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar o conteúdo da aplicação
COPY . .

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
CMD ["php-fpm"]