FROM intrasistema/php83-apache-cloudrun

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y tzdata
ENV TZ=Europe/Budapest
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt update && apt -y install netcat-openbsd zlib1g-dev libpng-dev libjpeg-dev
RUN docker-php-ext-configure gd --with-jpeg && docker-php-ext-install mysqli pdo pdo_mysql gd && a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x ./docker/app/entrypoint.sh

ENTRYPOINT [ "./docker/app/entrypoint.sh" ]
