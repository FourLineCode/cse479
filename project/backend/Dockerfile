FROM php:latest

WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git p7zip-full

COPY . .
RUN composer install

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host", "0.0.0.0"]
