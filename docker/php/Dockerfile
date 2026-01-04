FROM php:8.2-fpm

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

# Install dependencies and extensions in ONE GO to avoid missing packages
RUN apt-get clean && apt-get update --fix-missing && apt-get upgrade -y && \
    apt-get install -y \
    apt-transport-https \
    apt-utils \
    ca-certificates \
    curl \
    dirmngr \
    git \
    gnupg2 \
    inetutils-ping \
    libicu-dev \
    libmcrypt-dev \
    libmemcached-dev \
    libonig-dev \
    libpq-dev \
    libxslt-dev \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libfreetype-dev \
    lsb-release \
    make \
    unzip \
    wget \
    supervisor \
    cron \
    libxrender1 \
    libfontconfig \
    nano \
    nodejs \
    npm \
    zip \
    libcurl4-gnutls-dev && \
    # --- PHP Extensions Installation ---
    docker-php-ext-install mbstring zip xsl soap bcmath intl posix pcntl opcache && \
    # Configure and Install GD
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd && \
    # Install MySQL
    docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
    docker-php-ext-configure mysqli --with-mysqli=mysqlnd && \
    docker-php-ext-install mysqli pdo pdo_mysql && \
    # Install Redis
    pecl install -o -f redis && docker-php-ext-enable redis && rm -rf /tmp/pear && \
    # Install Memcached
    pecl install memcached && docker-php-ext-enable memcached && \
    # Install Sockets (Standard way)
    docker-php-ext-install sockets && \
    # Install Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    # Install Gosu
    wget --no-check-certificate -q -O /usr/local/bin/gosu "https://github.com/tianon/gosu/releases/download/1.10/gosu-amd64" && \
    chmod +x /usr/local/bin/gosu && \
    # Cleanup to reduce image size
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Development php.ini settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

###########################################################################
# xDebug:
###########################################################################

ARG INSTALL_XDEBUG
RUN if [ "${INSTALL_XDEBUG}" = "true" ]; then \
  pecl install xdebug && \
  docker-php-ext-enable xdebug && \
  touch /var/log/xdebug.log; \
fi

# Copy configuration
COPY ./conf.d/00-xdebug.ini /usr/local/etc/php/conf.d/00-xdebug.ini
COPY ./conf.d/50-custom.ini /usr/local/etc/php/conf.d/
COPY ./conf.d/z-opcache.ini /usr/local/etc/php/conf.d/
COPY ./php-fpm.pool.conf  /usr/local/etc/php-fpm.d/
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./supervisor.dev/ /etc/supervisor/conf.d/
RUN rm -f /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf.default

# Install Russian root certs
COPY ./install_ru_certs.sh /tmp/install_ru_certs.sh
RUN sed -i 's/\r$//' /tmp/install_ru_certs.sh && \
    chmod +x /tmp/install_ru_certs.sh && \
    /tmp/install_ru_certs.sh && \
    rm /tmp/install_ru_certs.sh
# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

RUN cp -r /etc/skel /home/www-data \
    && mkdir -p /home/www-data/.composer \
    && chown -R www-data:www-data /home/www-data \
    && usermod -d /home/www-data www-data \
    && sed -i "s/var\/run\/supervisor.sock/home\/www-data\/supervisor.sock/" /etc/supervisor/supervisord.conf \
    && mkdir -p /var/log/supervisor \
    && chown www-data:www-data -R /var/log/supervisor/

RUN if [ "${INSTALL_XDEBUG}" = "true" ]; then \
    chown www-data:www-data /var/log/xdebug.log; \
fi

# Cron
RUN chmod gu+rw /run && chmod gu+s /usr/sbin/cron && chmod gu+s /usr/bin/crontab
COPY ./crontab /tmp/crontab
# Исправляем Windows-переносы строк и устанавливаем крон
RUN sed -i 's/\r$//' /tmp/crontab && \
    /usr/bin/crontab -u www-data /tmp/crontab && \
    rm /tmp/crontab

# Configure locale.
ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

WORKDIR /var/www

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]

EXPOSE 9000
