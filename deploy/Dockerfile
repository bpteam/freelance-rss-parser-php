ARG PHP_VERSION=8.2.6-cli-alpine3.18

FROM php:$PHP_VERSION

RUN apk add --no-cache tini fcgi zip libzip-dev && docker-php-ext-install zip \
    && apk add --no-cache postgresql-dev && docker-php-ext-install pdo pdo_pgsql \
    && apk add --no-cache build-base autoconf php82-pear php82-dev gcc musl-dev make git \
    && docker-php-ext-install bcmath \
    && apk add --no-cache icu-dev && docker-php-ext-install intl

ARG APP_VERSION
ENV VERSION $APP_VERSION

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APP_ENV prod

WORKDIR /app

COPY . /app
COPY deploy/cron.txt /app

RUN /usr/bin/composer install --no-dev --optimize-autoloader
RUN php /app/bin/console cache:clear

ENV APP_ENV prod
ENV APP_DEBUG 0

RUN rm -rf /app/deploy /app/tests

WORKDIR /app

RUN /usr/bin/crontab /app/cron.txt

CMD ["/sbin/tini", "--", "/usr/sbin/crond", "-f"]