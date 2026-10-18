# build base image to use in docker-compose
sudo docker build -f ./docker/app-base/Dockerfile --target app_dev -t app_base_image .

#run composer install form host
docker run --rm --interactive --tty  --volume $PWD:/app composer install --ignore-platform-reqs --no-scripts

chmod +x backend/bin/jwt_key.sh
cd  backend && bin/jwt_key.sh

mkdir var
mkdir var/cache && chmod  -R 777 var/cache
mkdir var/log && chmod  -R 777 var/log
mkdir public/bundles && chmod  -R 777 public/bundles


chmod +x backend/bin/first-install.sh
sudo docker exec $(sudo docker ps -q -f name=sw4_worker) /bin/ash /var/www/html/backend/bin/first-install.sh
sudo docker exec $(sudo docker ps -q -f name=sw4_worker) php /var/www/html/backend/bin/console assets:install

sudo docker exec -it sw4_worker_1 ash


#DEV
sudo docker exec $(sudo docker ps -q -f name=ec2-user_worker_1) php /var/www/html/backend/bin/console cron:feed:level1:feed-new
