#
##################################
# BASE ENVIRONMENT
##################################
#
FROM php:8.1-fpm as base
# update all repositories
RUN apt-get -qq update -y
# WGET
RUN apt install wget
#mysql client
RUN apt-get -qq install -y default-mysql-client
# GIT
RUN apt-get -qq install -y git
# ZIP
RUN apt -y -qq install libzip-dev zip \
    && docker-php-ext-install zip
# OPCACHE
RUN docker-php-ext-install opcache
# PDO
RUN apt-get -qq install libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-install pdo_mysql
# SOAP
RUN apt install -y libxml2-dev \
    && docker-php-ext-install soap
# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug-3.1.4

# INTL
RUN apt-get -qq install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
# GD
RUN apt-get install -y libpng-dev libjpeg-dev libonig-dev libxml2-dev
RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd
# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath
# NPM
#RUN mkdir /tmp
RUN curl -sL https://deb.nodesource.com/setup_16.x -o /tmp/nodesource_setup.sh
#RUN chmod +x /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt install nodejs
RUN node -v
RUN npm -v
#RUN apt install -y npm
# CUSTOM INI FILES
COPY ./docker/php/ini/php.ini /usr/local/etc/php/conf.d/custom.ini
# WKHTML2PDF
RUN apt install -y xfonts-75dpi xfonts-base fontconfig libxrender1
RUN wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.5/wkhtmltox_0.12.5-1.buster_amd64.deb
RUN dpkg -i wkhtmltox_0.12.5-1.buster_amd64.deb
# FFMPEG
RUN apt install -y ffmpeg
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY . /app
WORKDIR /app
ENV COMPOSER_ALLOW_SUPERUSER=1a


# install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN chmod -R 0777 storage
#RUN npm install --fix-missing
