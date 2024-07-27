apt-get update
apt install mysql-server
mysql_secure_installation

CREATE USER 'sw4'@'%' IDENTIFIED WITH mysql_native_password BY 'Jd3_jN2dn01iPXd3eMp_';
GRANT ALL PRIVILEGES ON *.* TO 'sw4'@'%';
FLUSH PRIVILEGES;
exit;
mysql -u sw4 -p

# allow remote
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

bind-address            = 0.0.0.0

#pun to section
[mysqld]
character-set-server=utf8
disable-log-bin


