FROM php:8.2-cli 
 
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libpq-dev# Install system dependencies
RUN apt-get update && apt-get install -y \
build-essential \
libpng-dev \
libjpeg-dev \
libfreetype6-dev \
locales \
zip \
jpegoptim optipng pngquant gifsicle \
vim \
unzip \
git \
curl \
libpq-dev \
libonig-dev  \
procps  

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* 

RUN docker-php-ext-install pdo_pgsql  
 
# Install composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

WORKDIR /usr/src/myapp

COPY composer.json composer.lock artisan ./

COPY . .

# Set permissions
RUN chown -R www-data:www-data /usr/src/myapp/storage /usr/src/myapp/bootstrap/cache

RUN composer install    
 

EXPOSE 8000 
CMD ["sh", "-c", "sleep 3 && php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=8000"] 