FROM php:8-apache

RUN a2enmod rewrite
RUN a2enmod cgi
RUN a2enmod headers
RUN a2enmod cgid

RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN apt-get update && apt-get install -y \
    curl \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libbz2-dev \
    aptitude \
    libmagickwand-dev \
    libmcrypt-dev \
    libxslt1-dev \
    default-mysql-server \
    libmariadbd-dev \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN pecl install imagick && docker-php-ext-enable imagick
RUN docker-php-ext-install bz2
RUN docker-php-ext-install calendar
RUN docker-php-ext-install exif
RUN docker-php-ext-install gettext
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip

# installs nvm (Node Version Manager)
# Install npm
ENV NODE_VERSION=18.5.0
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

WORKDIR /var/www/html
COPY . /var/www/html
RUN chmod -R 777 /var/www/html/public

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY apache.conf /etc/apache2/apache2.conf
COPY php-settings.ini /usr/local/etc/php/php.ini

RUN composer install

EXPOSE 80

CMD apache2-foreground
