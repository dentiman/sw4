# in host need folders for docker volumes:
#1) Copy manual folder letsencrypt from project to host
#2) up server
#3) remove default certs before generate new
rm -Rf letsencrypt/etc/live/stock-watcher.com
rm -Rf letsencrypt/etc/live/next.stock-watcher.com


docker run -it --rm --name certbot \
            -v "$PWD/letsencrypt/etc:/etc/letsencrypt" \
            -v "$PWD/letsencrypt/web:/var/www/html" certbot/certbot \
            certonly --webroot --webroot-path=/var/www/html --force-renewal --email support@stock-watcher.com  --register-unsafely-without-email  --agree-tos --no-eff-email  -d stock-watcher.com

docker run -it --rm --name certbot \
            -v "$PWD/letsencrypt/etc:/etc/letsencrypt" \
            -v "$PWD/letsencrypt/web:/var/www/html" certbot/certbot \
            certonly --webroot --webroot-path=/var/www/html --force-renewal --email support@stock-watcher.com  --register-unsafely-without-email  --agree-tos --no-eff-email  -d next.stock-watcher.com

# !!!!!!!
# run every time after generate certs
sudo chmod -R  0755 letsencrypt

docker stack rm sw4
docker stack deploy -c docker-stack.yaml sw4


#if some issue need  delete on host files inside volume but do not delete volume folder
sudo rm -Rf letsencrypt/etc/*
sudo rm -Rf letsencrypt/web/*


#RENEW
docker run -it --rm --name certbot \
            -v "$PWD/letsencrypt/etc:/etc/letsencrypt" \
            -v "$PWD/letsencrypt/web:/var/www/html" certbot/certbot renew
