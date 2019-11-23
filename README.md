# yanue.net

```
yum install php
yum install php-devel php-pdo php-mysqlnd
yum install php-fpm
```

vi /etc/php-fpm.d/www.conf
```
listen = /run/php-fpm/php-fpm.sock
```

yum install mariadb mariadb-server
systemctl start mariadb
systemctl enable mariadb
mysql_secure_installation
