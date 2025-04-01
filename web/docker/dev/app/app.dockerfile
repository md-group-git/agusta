FROM alpine:3.12

RUN apk add --update \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo\
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-xmlwriter \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xdebug \
    php7-zip \
    imagemagick \
    ffmpeg \
    make \
    curl \
    nodejs \
    yarn

RUN set -x \
    && addgroup -g 82 -S www-data \
    && adduser -u 1000 -D -S -G www-data www-data

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

COPY project.ini /etc/php7/conf.d/
COPY project.ini /etc/php7/cli/conf.d/
COPY project-fpm.conf /etc/php7/php-fpm.d/

CMD ["php-fpm7", "-F"]

WORKDIR /var/www/project

EXPOSE 9001
