FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite

COPY . /var/www/html/

RUN pip3 install --break-system-packages -r /var/www/html/librerias.txt

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Script de arranque
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
EXPOSE 5000

CMD ["/start.sh"]
