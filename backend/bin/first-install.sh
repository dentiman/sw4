#!/bin/sh

php /var/www/html/backend/bin/console doctrine:schema:update --force
php /var/www/html/backend/bin/console import:init-data
php /var/www/html/backend/bin/console cron:definitions:load
php /var/www/html/backend/bin/console cron:feed:tickers
php /var/www/html/backend/bin/console cron:feed:yahoo:quote
php /var/www/html/backend/bin/console cron:feed:yahoo:tech
php /var/www/html/backend/bin/console cron:feed:earnings:calendar
php /var/www/html/backend/bin/console cron:feed:tmp
