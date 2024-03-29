FROM php:7.4-fpm

# Copy composer.lock and composer.json
# COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www/public/

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip  libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    supervisor

RUN pecl install xdebug-2.9.7 \
    && docker-php-ext-enable xdebug

RUN { \
    echo 'zend_extension=xdebug.so'; \
    echo 'xdebug.remote_enable=1'; \
    echo "xdebug.remote_autostart=1"; \
    echo 'xdebug.remote_host=host.docker.internal'; \
    echo 'xdebug.remote_port=9001'; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www


# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD composer install ; php-fpm