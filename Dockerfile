FROM php:5.6

RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    mongodb \
    php5-mongo \
    git \
    sudo

RUN cp /usr/share/php5/php.ini-production.cli /usr/local/etc/php/php.ini \
    && echo "extension=mongo.so" >> /usr/local/etc/php/php.ini \
    && cp `dpkg -L php5-mongo | grep mongo.so` `php-config --extension-dir` \
    && echo 'date.timezone = "Europe/Amsterdam"' >>/usr/local/etc/php/php.ini \
    && echo 'smallfiles = true' >> /etc/mongodb.conf \
    && echo 'quotaFiles = 1' >> /etc/mongodb.conf

VOLUME ["/var/lib/mongodb"]

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

USER root
CMD /var/www/html/build/startup.sh

#CMD /bin/bash
