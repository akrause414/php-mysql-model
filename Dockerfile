FROM php:7-alpine AS base

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM base AS deps
WORKDIR /app
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --prefer-dist

FROM deps AS test
WORKDIR /app
COPY . .
RUN ./vendor/bin/phpunit -c phpunit.xml
#ENTRYPOINT ["tail", "-f", "/dev/null"]