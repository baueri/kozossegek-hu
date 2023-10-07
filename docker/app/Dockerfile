FROM zareef/php81-apache

WORKDIR /app

COPY . .
RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x ./docker/app/entrypoint.sh
RUN apt update && apt install netcat-openbsd dnf -y
RUN sudo yum update
RUN npm install --global gulp-cli && npm install --save-dev gulp bulma gulp-rename gulp-concat gulp-clean && npm audit fix --force

ENTRYPOINT [ "./docker/app/entrypoint.sh" ]
