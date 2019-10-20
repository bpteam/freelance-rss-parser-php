ARG MIDDLEWARE_VERSION
FROM freelance-rss-parser-php:$MIDDLEWARE_VERSION AS BUILD_CONTEXT

ARG COMPOSER_AUTH
ARG PRIVATE_REPOSITORY_USERNAME
ARG PRIVATE_REPOSITORY_PASSWORD
ARG PRIVATE_REPOSITORY_HOST
ARG PRIVATE_REPOSITORY_PATH
ARG APP_VERSION
ENV VERSION $APP_VERSION

COPY --from=composer:1.9.0 /usr/bin/composer /usr/bin/composer

ENV APP_ENV prod

RUN apk add --no-cache git

RUN git clone -b "$APP_VERSION" --single-branch --depth 1 https://$PRIVATE_REPOSITORY_USERNAME:$PRIVATE_REPOSITORY_PASSWORD@$PRIVATE_REPOSITORY_HOST/$PRIVATE_REPOSITORY_PATH /app

RUN chown -R www-data /app

ENV COMPOSER_AUTH $COMPOSER_AUTH
ENV COMPOSER_HOME /tmp/composer

RUN mkdir -p ${COMPOSER_HOME}
RUN chown -R www-data /tmp/composer

USER www-data

WORKDIR /app

RUN mkdir -p /tmp/composer
RUN /usr/bin/composer install --no-dev --optimize-autoloader
RUN /usr/bin/composer dump-autoload --optimize
RUN php /app/bin/console cache:clear

USER root

RUN rm -rf /usr/bin/composer
RUN rm -rf ${COMPOSER_HOME}
RUN apk del git

FROM freelance-rss-parser-php:$MIDDLEWARE_VERSION

ENV APP_ENV prod

COPY --from=BUILD_CONTEXT /app/environment/php/production/*.ini /usr/local/etc/php/conf.d/

COPY --chown=www-data:www-data --from=BUILD_CONTEXT /app /app
RUN rm -rf /app/environment

WORKDIR /app

USER www-data