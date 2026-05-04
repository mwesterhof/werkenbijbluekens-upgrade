FROM php:8.3-apache

LABEL maintainer="Marco Westerhof"

ARG NODE_VERSION=22

WORKDIR /var/www/html

ENV SUPERVISOR_RUN_QUEUED_WORKERS="php /var/www/html/bin/console messenger:consume async --time-limit=3600"
ENV SUPERVISOR_PHP_USER="www-data"
ENV APP_DOMAIN="localhost"

# Install packages
RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libffi-dev \
    libmagickwand-dev \
    inkscape \
    supervisor

# Install PHP extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install -j$(nproc) intl pdo pdo_mysql opcache zip ffi gd
RUN docker-php-ext-install sysvsem
RUN pecl install imagick
RUN docker-php-ext-enable imagick

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_HOME /tmp/.composer

# Install Node.js and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - && \
    apt-get update && apt-get install -y nodejs
RUN npm install -g npm@11.11.1

RUN curl -fsSL https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor -o /usr/share/keyrings/yarnkey.gpg && \
    echo "deb [signed-by=/usr/share/keyrings/yarnkey.gpg] https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn
ENV YARN_CACHE_FOLDER=/tmp/.yarn-cache

# Enable development PHP configuration and copy project configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY php-ini-overrides.ini "$PHP_INI_DIR/conf.d/php-ini-overrides.ini"

# Enable apache modules and copy project configuration
RUN /usr/sbin/a2enmod rewrite && /usr/sbin/a2enmod headers && /usr/sbin/a2enmod expires
COPY apache.conf "$APACHE_CONFDIR/sites-available/000-default.conf"
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/start-container

# Adjust 'www-data' Ownership/Permissions
RUN chown -R www-data:www-data /var/www
RUN chown -R www-data:www-data /var/www/html

# Set default user and working directory
USER www-data

EXPOSE 80/tcp

ENTRYPOINT ["start-container"]
