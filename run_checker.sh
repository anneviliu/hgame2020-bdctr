#! /bin/bash
service apache2 start
sleep 5
service mysql start
sleep 5
mysql -uroot -e "create database team_token"
mysql -uroot team_token < /home/token.sql
mysql -uroot -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '5Om1HtOwkW31AYjHn3bOAyJDr5bSQriXRgUoNpK54ELSE';"
while true
	do echo 1
	sleep 5
done
