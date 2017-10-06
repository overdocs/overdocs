FROM php:7.1-fpm
MAINTAINER Olgierd Grzyb <kontakt@olgierd.me>
WORKDIR /data/app
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libxslt1-dev \
        git-core \
        zip \
        unzip \
        nodejs \
        nodejs-legacy \
        npm
RUN docker-php-ext-install -j$(nproc) posix iconv mcrypt xsl pdo_mysql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd
RUN npm install -g gulp
COPY composer /usr/local/bin/composer
COPY setup.sh /tmp/setup.sh
RUN bash /tmp/setup.sh
EXPOSE 9000
