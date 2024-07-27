#https://linuxize.com/post/how-to-install-and-configure-redis-on-ubuntu-18-04/
sudo apt update
sudo apt install redis-server
sudo nano /etc/redis/redis.conf

#Locate the line that begins with bindsudo systemctl restart redis-server 127.0.0.1 ::1 and replace 127.0.0.1 with 0.0.0.0.
# bind 0.0.0.0 ::1
#protected-mode no

sudo systemctl restart redis-server
#redis-cli -h <REDIS_IP_ADDRESS> ping
