FROM php:8.0-fpm
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./php.ini /usr/local/etc/php/
RUN apt-get update \
  && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev libonig-dev \
  && docker-php-ext-install pdo_mysql mysqli gd iconv exif

RUN apt-get install -y git cron vim

RUN docker-php-source extract \
  && docker-php-source delete

RUN git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis && \
  docker-php-ext-install redis