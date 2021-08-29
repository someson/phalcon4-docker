FROM php:7.4-fpm-alpine

ENV PHALCON_VERSION=4.1.2 \
    PHALCON_EXT_PATH=php7/64bits \
    PSR_VERSION=1.1.0 \
    APCU_VERSION=5.1.20
#    DEVTOLLS_VERSION=4.0.3

RUN apk update && apk add ca-certificates && rm -rf /var/cache/apk/*
COPY php/cert/cacert.pem /usr/local/share/ca-certificates/cacert.pem
RUN chmod 644 /usr/local/share/ca-certificates/cacert.pem && update-ca-certificates

RUN apk add git \
    && apk add --no-cache --virtual .persistent-deps \
    libxml2-dev \
    # for intl extension
    icu-libs

RUN set -xe \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        icu-libs \
    && pecl install apcu-${APCU_VERSION} apcu_bc xdebug \
    && docker-php-ext-enable xdebug apcu \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql \
    && docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install -j$(nproc) \
        calendar \
        intl \
        pdo_mysql \
    && pecl clear-cache \
    && apk del .build-deps \
    && docker-php-source delete \
    && rm -rf /tmp/* /var/cache/apk/*

RUN set -xe \
    && curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz \
    && tar xzf ${PWD}/v${PSR_VERSION}.tar.gz \
    && curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz \
    && tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz \
    && docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
    && rm -r \
        ${PWD}/v${PSR_VERSION}.tar.gz \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/v${PHALCON_VERSION}.tar.gz \
        ${PWD}/cphalcon-${PHALCON_VERSION} \
    && php -m

# phalcon devtools has some issues currently:
# https://github.com/phalcon/phalcon-devtools/issues/1431
# https://github.com/phalcon/phalcon-devtools/issues/1378
# we need update the package with "composer update". Remove composer from container after issues are fixed
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN set -xe \
#    && curl -LO https://github.com/phalcon/phalcon-devtools/archive/v${DEVTOLLS_VERSION}.tar.gz \
#    && tar xzf v${DEVTOLLS_VERSION}.tar.gz \
#    && rm -rf v${DEVTOLLS_VERSION}.tar.gz \
#    && mv phalcon-devtools-${DEVTOLLS_VERSION} /usr/src/phalcon-devtools \
#    && ln -sf /usr/src/phalcon-devtools/phalcon /usr/local/bin/phalcon \
#    && cd /usr/src/phalcon-devtools \
#    && composer update --ignore-platform-reqs \
#    && phalcon --version

COPY php/php.ini /usr/local/etc/php/conf.d/php-override.ini
COPY fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY fpm/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN touch /var/log/remote.log && chmod 666 /var/log/remote.log

WORKDIR /var/www/html
