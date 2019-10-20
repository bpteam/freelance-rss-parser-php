ARG MIDDLEWARE_VERSION
FROM freelance-rss-parser-php:$MIDDLEWARE_VERSION

USER root

RUN apk add --no-cache php7-pear php7-dev gcc musl-dev make git \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /app

COPY --from=composer:1.9.0 /usr/bin/composer /usr/bin/composer

RUN mkdir --mode=777 -p /.composer