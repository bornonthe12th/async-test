FROM php:8.2-cli

# Install system dependencies and build tools
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    autoconf \
    gcc \
    g++ \
    make \
    libtool \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Install Xdebug
RUN echo "🔧 Installing Xdebug..." && \
    pecl install xdebug

# Enable Xdebug manually (no quote hell)
RUN ext_dir="$(php -i | grep '^extension_dir' | awk '{print $3}')" && \
    echo "zend_extension=${ext_dir}/xdebug.so" > /usr/local/etc/php/conf.d/xdebug-ext.ini

# Configure Xdebug
RUN echo "xdebug.mode=debug\n\
xdebug.start_with_request=yes\n\
xdebug.client_host=host.docker.internal\n\
xdebug.client_port=9003\n\
xdebug.log=/tmp/xdebug.log" > /usr/local/etc/php/conf.d/xdebug-config.ini

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Optional: Verify Xdebug install
RUN php -m | grep xdebug && php -i | grep -i xdebug

# Run the script
CMD ["php", "test-async.php"]
