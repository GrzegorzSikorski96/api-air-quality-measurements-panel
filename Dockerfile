FROM php:8.3-fpm

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions pdo_pgsql opcache amqp zip sockets

RUN apt-get update  \
    && apt-get install -y supervisor \
    && apt-get clean -y && apt-get autoclean -y apt-get autoremove -y && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ADD https://github.com/fabpot/local-php-security-checker/releases/download/v1.0.0/local-php-security-checker_1.0.0_linux_amd64 /usr/bin/local-php-security-checker

RUN chmod +x /usr/bin/local-php-security-checker