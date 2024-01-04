FROM php:8.3-fpm

RUN apt-get update \
    && apt-get install -y \
        libpng-dev \
        libxml2-dev \
        zlib1g-dev \
        libonig-dev \
        libzip-dev \
        zip \
        apt-utils \
        libbz2-dev \
        libicu-dev \
        mc \
        nano \
        htop \
        git \
        systemd \
    	sudo

RUN docker-php-ext-configure intl
RUN docker-php-ext-install mbstring gd zip bcmath pdo sockets intl

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions pdo_pgsql opcache amqp zip sockets

RUN apt-get update  \
    && apt-get clean -y && apt-get autoclean -y apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

RUN git clone https://github.com/xdebug/xdebug.git
RUN cd xdebug && phpize && ./configure && make && make install
COPY docker/php/php-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ADD https://github.com/fabpot/local-php-security-checker/releases/download/v2.0.6/local-php-security-checker_2.0.6_linux_amd64 /usr/bin/local-php-security-checker

RUN chmod +x /usr/bin/local-php-security-checker