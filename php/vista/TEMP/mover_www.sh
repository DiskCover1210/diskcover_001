#!/bin/bash
#
#Conectarse: diskcover||Dlcjvl1210
clear
echo "Iniciando el Proceso"
cd /home/diskcover/public_html
cp * /var/www/html -R
chown apache:apache /var/www/html -R
chmod a+x /var/www/html/*.php -R
chmod a+x /var/www/html/*.sh -R
echo "Proceso terminado"
