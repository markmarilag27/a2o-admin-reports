FROM php:8.4-fpm-bullseye

ARG DEBIAN_FRONTEND=noninteractive

# System packages
RUN apt-get update && apt-get install -y entr \
    git unzip curl ca-certificates gnupg \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    libzip-dev libcurl4-openssl-dev libpq-dev libicu-dev \
    libmagickwand-dev libssl-dev zlib1g-dev \
    jpegoptim optipng pngquant webp \
    && curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd intl mbstring xml bcmath opcache exif pcntl \
    && pecl install redis xdebug imagick \
    && docker-php-ext-enable redis xdebug imagick \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Custom config
COPY php.ini /usr/local/etc/php/conf.d/99-custom.ini

# Laravel user
RUN groupadd -g 1000 laravel && useradd -u 1000 -ms /bin/bash -g laravel laravel

# Create and chown the log directory/file
RUN mkdir -p /usr/local/var/log \
 && touch /usr/local/var/log/php-fpm.log \
 && chown -R laravel:laravel /usr/local/var/log

USER laravel
WORKDIR /var/www/html

EXPOSE 9070

CMD ["php-fpm", "-F", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
