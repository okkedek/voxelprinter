FROM php:7.0.6

WORKDIR /var/www/html/

COPY . .

EXPOSE 8000

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN bin/console assets:install web

ONBUILD RUN composer update

CMD bin/console server:run 0.0.0.0:8000