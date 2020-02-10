#! /bin/bash
service apache2 start
sleep 5
service mysql start
sleep 5
mysql -uroot -e "create database bdctr_message"
mysql -uroot bdctr_message < /home/app.sql
mysql -uroot -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'yevi1gcqpqHSaOZVDI1CcRLaHHSJ5BYgImof';"
chown www-data:www-data /var/www/html/sandbox/
while true
	do echo 1
	sleep 5
done
