FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
        build-essential libpng-dev \
        libjpeg62-turbo-dev zip \
        libfreetype6-dev locales \
        jpegoptim optipng pngquant gifsicle \
        curl libxml2-dev libssl-dev zlib1g-dev \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libc-client-dev libkrb5-dev unzip git

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql sockets mbstring zip exif pcntl soap mysqli bcmath
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd imap

RUN pecl install -o -f redis \
        &&  rm -rf /tmp/pear \
        &&  docker-php-ext-enable redis
RUN curl -sS https://getcomposer.org/installer | php -- \
      --install-dir=/usr/local/bin --filename=composer --version=1.10.22
