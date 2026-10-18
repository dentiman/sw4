FROM ubuntu:18.04
RUN apt-get update
RUN apt install -y software-properties-common
RUN  add-apt-repository ppa:ondrej/php


ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN  apt-get update && apt-get install -y --no-install-recommends  \
     curl \
     git \
     composer\
     imagemagick \
     libmagickwand-dev \
     php7.3 php7.3-fpm php7.3-mysql \
     php7.3-curl php7.3-imap php7.3-json php7.3-mbstring \
     php7.3-gd php7.3-zip php7.3-redis php7.3-intl php7.3-xml php7.3-imagick

### NGINX
RUN apt-get install -y nginx
#COPY ./nginx/sw /etc/nginx/sites-available/
#RUN mkdir /etc/nginx/ssl
#COPY ./nginx/next_stock_watcher.crt /etc/nginx/ssl/
#COPY ./nginx/next_stock_watcher.key /etc/nginx/ssl/
#RUN ln -s /etc/nginx/sites-available/sw /etc/nginx/sites-enabled
#RUN service nginx restart

EXPOSE 80 81 443
