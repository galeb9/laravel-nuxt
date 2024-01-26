FROM php:8.0-fpm-alpine3.14 AS ext-stats

RUN docker-php-source extract \
    && apk add git

## Install phase
FROM php:8.0-fpm-alpine3.14 AS installer

# build paramaters
ARG env=dev

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apk add --no-cache build-base autoconf libzip-dev mariadb-client freetype-dev libpng-dev libjpeg-turbo-dev curl imap-dev openssl-dev icu-dev python2

# install node only on dev
RUN apk add bash --update nodejs npm yarn

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl
RUN docker-php-ext-install imap
RUN docker-php-ext-install calendar
RUN pecl channel-update pecl.php.net && \
    pecl install apfd
	
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN addgroup -g 1001 www
RUN adduser -u 1001 -S -G www www

# add PHP configurations
COPY docker/php/ /usr/local/etc/php/conf.d/

# Copy existing application directory permissions
COPY --chown=www:www src/backend /var/www

# Install composer dependencies and generate
RUN composer install

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENV HOST 0.0.0.0
CMD ["php-fpm"]