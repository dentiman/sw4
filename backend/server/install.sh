#!/bin/bash

# sudo apt update

#
# sudo apt install software-properties-common
# sudo add-apt-repository ppa:ondrej/php
# apt update
#
# apt-get install -y --no-install-recommends \
#             curl git composer redis-server imagemagick \
#
# apt install php7.3 php7.3-fpm php7.3-mysql \
#  php7.3-curl php7.3-imap php7.3-json php7.3-mbstring \
#  php7.3-gd php7.3-zip php7.3-redis php7.3-intl php7.3-xml php7.3-imagick \
# cp ./php/custom.ini /etc/php/7.3/fpm/conf.d/
rm /etc/php/7.3/fpm/pool.d/www.conf
cp ./php/www.conf /etc/php/7.3/fpm/pool.d/


# apt install mysql-server
# mysql_secure_installation

# CREATE USER 'sw2'@'localhost' IDENTIFIED WITH mysql_native_password BY 'Jd3_jN2dn01iPXd3eMp_';
# GRANT ALL PRIVILEGES ON *.* TO 'sw2'@'localhost';
# FLUSH PRIVILEGES;
# exit;
# mysql -u sw4 -p


### NGINX
# sudo apt install nginx
# cp ./nginx/sw /etc/nginx/sites-available/
# mkdir /etc/nginx/ssl
cp ./nginx/next_stock_watcher.crt /etc/nginx/ssl/
# cp ./nginx/next_stock_watcher.key /etc/nginx/ssl/
# ln -s /etc/nginx/sites-available/sw /etc/nginx/sites-enabled
# sudo systemctl restart nginx
#
# chown www-data -R backend


### CRON JOBS
# cp ./scheduler /etc/cron.d/
# chmod -R 644 /etc/cron.d
# crontab /etc/cron.d/scheduler
# ln -snf /usr/share/zoneinfo/America/New_York /etc/localtime && echo 'America/New_York' > /etc/timezone
