FROM zareef/php81-apache

WORKDIR /app

COPY . .
RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x ./docker/app/entrypoint.sh

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install
RUN composer migrate

CMD "./docker/app/entrypoint.sh"
