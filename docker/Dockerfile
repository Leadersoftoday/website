FROM php:5.6-apache

RUN a2enmod rewrite
RUN apt-get update
RUN apt-get install -y git unzip

WORKDIR /var/www
COPY . ./

RUN curl https://getcomposer.org/installer | php
RUN php composer.phar install