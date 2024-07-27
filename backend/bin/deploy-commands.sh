#!/bin/sh
php /var/www/html/backend/bin/console doctrine:schema:update --force
#php /var/www/html/backend/bin/console import:liqpay