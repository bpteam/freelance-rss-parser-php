ARG PHP_IMAGE
FROM php:$PHP_IMAGE

RUN apk add --no-cache --virtual .php-build php7-pear php7-dev gcc musl-dev make \
    && apk add --no-cache zip libzip-dev && docker-php-ext-configure zip --with-libzip=/usr/include && docker-php-ext-install zip \
    && apk add --no-cache postgresql-dev && docker-php-ext-install pdo pdo_pgsql \
    && pecl install ds && docker-php-ext-enable ds \
    && apk del .php-build
