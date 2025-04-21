FROM php:7.3-cli

# Install system tools
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install specific version of Xdebug for PHP 7.3
RUN pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug

# ✅ Only custom config here (no zend_extension line)
RUN echo "zend_extension=xdebug.so\n\
xdebug.remote_enable=1\n\
xdebug.remote_autostart=1\n\
xdebug.remote_host=host.docker.internal\n\
xdebug.remote_port=9000\n\
xdebug.idekey=PHPSTORM\n\
xdebug.remote_log=/tmp/xdebug.log" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

CMD ["php", "test-async.php"]
