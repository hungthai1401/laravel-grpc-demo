FROM composer:2.7.9 AS vendor

FROM ghcr.io/roadrunner-server/roadrunner:2024 AS roadrunner

FROM php:8.2-cli-alpine

ARG UID
ARG GID
ARG GITLAB_PAT
ARG WORKDIR

ENV UID=${UID:-1000}
ENV GID=${GID:-1000}
ENV USER=${USER:-laravel}
ENV WORKDIR=${WORKDIR:-/var/www/html}

WORKDIR ${WORKDIR}

COPY --chown=${UID}:${UID} --from=vendor /usr/bin/composer /usr/bin/composer
COPY --chown=${UID}:${UID} --from=roadrunner /usr/bin/rr /usr/local/bin/rr

RUN apk update && apk upgrade \
    && apk add git \
    && rm -rf /var/cache/apk/* \
    && curl -sSLf \
        -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions opcache zip intl sockets grpc protobuf pcntl bcmath pdo_mysql \
    && addgroup -g ${GID} --system ${USER} \
    && adduser -g ${GID} --system -D -s /bin/sh -u ${UID} ${USER}

USER ${USER}

WORKDIR /var/www/html

EXPOSE 9001

COPY --chown=${UID}:${UID} composer.* .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-scripts \
    && composer clear-cache

COPY --chown=${UID}:${UID} . .
COPY --chown=${UID}:${UID} start-container.sh /usr/local/bin/start-container.sh

RUN composer dump-autoload --optimize \
    && mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache \
    && chmod -R a+rw storage \
    && chmod +x /usr/local/bin/start-container.sh

ENTRYPOINT ["start-container.sh"]
