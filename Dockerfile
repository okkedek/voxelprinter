FROM php:7.0.6

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git

RUN useradd -Um web

WORKDIR /var/www/html/

ADD composer.* ./
ADD phpunit.* ./
ADD . .
RUN chown -R web.web .

EXPOSE 8000

USER web
RUN mkdir var && chmod 777 var
RUN php composer.phar install
RUN bin/console assets:install web

CMD bin/console server:run 0.0.0.0:8000