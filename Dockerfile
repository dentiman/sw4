FROM composer:2.1.3 as vendor

WORKDIR /tmp/

COPY ./backend/composer.json composer.json
COPY ./backend/composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


FROM alpine:3.12 as app_base_image
#RUN apk add --no-cache  --repository http://dl-cdn.alpinelinux.org/alpine/edge/community php

# Install packages and remove default server definition
RUN apk update

# dependencies required for running "phpize"
# these get automatically installed and removed by "docker-php-ext-*" (unless they're already installed)

RUN apk --no-cache  add \
  php7-ctype \
  php7-curl \
  php7-dom \
  php7-fpm \
  php7-gd \
  php7-intl \
  php7-json \
  php7-mbstring \
  php7-mysqli \
  php7-pdo \
  php7-tokenizer \
  php7-opcache \
  php7-openssl \
  php7-phar \
  php7-session \
  php7-xml \
  php7-xmlreader \
  php7-zlib \
  php7-iconv \
  php7-fileinfo \
  php7-simplexml \
  php7-dev \
  php7-redis


RUN apk --no-cache  add \
  curl \
  build-base \
    libmcrypt-dev \
    libxml2-dev \
    pcre-dev \
    zlib-dev \
    autoconf \
    cyrus-sasl-dev \
    libgsasl-dev \
    oniguruma-dev \
    libressl \
    libressl-dev \
    supervisor \
    git \
    file \
    make \
    libc-dev \
    acl

# persistent / runtime deps
RUN apk add --no-cache \
		ca-certificates \
		curl \
		tar \
		xz \
# https://github.com/docker-library/php/issues/494
		openssl

# Create symlink so programs depending on `php` still function
#RUN ln -s /usr/bin/php7 /usr/bin/php


ENV PHP_INI_DIR /etc/php7

COPY docker/webserver/ext/docker-php-source /usr/local/bin/
COPY docker/webserver/ext/docker-php-ext-* docker/webserver/ext/docker-php-entrypoint /usr/local/bin/
RUN chmod 777 /usr/local/bin/docker-php-entrypoint
RUN chmod 777 /usr/local/bin/docker-php-ext-*
RUN chmod 777 /usr/local/bin/docker-php-source



RUN apk --no-cache --upgrade add imagemagick imagemagick-dev

RUN   cd /tmp &&  git clone https://github.com/Imagick/imagick \
      && cd imagick &&  phpize && \
      ./configure  && \
      make && \
      make install \
      && rm -r /tmp/imagick \
      && docker-php-ext-enable imagick \
      && docker-php-source delete

RUN rm -rf /var/cache/apk/* \
    && apk del make file

# Configure PHP-FPM
COPY docker/webserver/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY docker/webserver/config/php.ini /etc/php7/conf.d/custom.ini

# Configure timezone
ARG TZ=America/New_York
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html

COPY --chown=nobody ./backend /var/www/html/backend
COPY --from=vendor /tmp/vendor/ /var/www/html/backend/vendor/
RUN  chmod 755 /var/www/html/backend/config/jwt
RUN  chmod +x /var/www/html/backend/bin/jwt_key.sh
RUN  chmod +x /var/www/html/backend/bin/first-install.sh
RUN  chmod +x /var/www/html/backend/bin/deploy-commands.sh
RUN cd /var/www/html/backend  && bin/jwt_key.sh && php bin/console assets:install


# WEBSERVER------------------------------
#
#
FROM app_base_image as webserver
USER root
RUN apk --no-cache add \
    nginx \
    && rm /etc/nginx/conf.d/default.conf

# Configure nginx
COPY docker/webserver/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/webserver/ssl/next_stock_watcher.crt /etc/nginx/ssl/next_stock_watcher.crt
COPY docker/webserver/ssl/next_stock_watcher.key /etc/nginx/ssl/next_stock_watcher.key

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx
  # chown -R nobody.nobody /var/www/html
# Configure supervisord
COPY docker/webserver/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

USER nobody
COPY --chown=nobody ./frontend/dist/ /var/www/html/frontend/
# Expose the port nginx is reachable on
EXPOSE 80 443

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]




FROM app_base_image as worker
USER root
# Configure supervisord
COPY docker/worker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chown -R nobody.nobody /var/www/html
USER nobody
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
