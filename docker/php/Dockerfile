FROM php:8.0-apache-buster

COPY ./apache2.conf /etc/apache2/

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get upgrade -y
RUN apt-get update -y
RUN apt-get -y install \
        libicu-dev \
        libzip-dev \
        libonig-dev \
        zip \
        unzip \
        vim \
        sudo \
        curl \
        libssl-dev  && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install intl mbstring opcache pdo_mysql mysqli zip

COPY ./php.ini /usr/local/etc/php/php.ini

RUN a2enmod rewrite \
    && service apache2 restart

WORKDIR /var/www/html