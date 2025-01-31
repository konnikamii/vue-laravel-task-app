FROM php:8.2-cli 
 
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev 
  
RUN docker-php-ext-install pdo_pgsql  
  
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

WORKDIR /usr/src/myapp
 
COPY . .

RUN composer install --no-cache
   
RUN chown -R www-data:www-data /usr/src/myapp/storage /usr/src/myapp/bootstrap/cache
   
EXPOSE 8000 
CMD ["sh", "-c", "sleep 3 && php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=8000"] 