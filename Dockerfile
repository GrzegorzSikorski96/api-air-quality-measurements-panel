FROM php:8.3-fpm

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions pdo_pgsql opcache amqp zip sockets

RUN apt-get update  \
    && apt-get install -y git \
    && apt-get clean -y && apt-get autoclean -y apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

ENV USER www-data

ENV PHP_FPM_PM="ondemand"
ENV PHP_FPM_MAX_CHILDREN="5000"
ENV PHP_FPM_START_SERVERS="2"
ENV PHP_FPM_MIN_SPARE_SERVERS="1"
ENV PHP_FPM_MAX_SPARE_SERVERS="20"
ENV PHP_FPM_MAX_REQUESTS="10000"
ENV PHP_MEMORY_LIMIT="-1"

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DATE_TIMEZONE=UTC

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ./docker/nginx/conf.d/default.conf /opt/docker/etc/nginx/conf.d/default.conf
COPY --chown=$USER ./ /app

RUN groupmod -g 1000 $USER

# XDEBUG
RUN git clone https://github.com/xdebug/xdebug.git
RUN cd xdebug && phpize && ./configure && make && make install
COPY docker/php/php-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN composer install

USER $USER
