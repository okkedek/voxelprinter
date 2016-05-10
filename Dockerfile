FROM php:7.0.6

WORKDIR /var/www/html/

COPY . .

EXPOSE 8000

RUN bin/console assets:install web

CMD bin/console server:run 0.0.0.0:8000