FROM intrasistema/php83-apache-cloudrun

# Prefer PHP from this image (PECL / docker-php-ext-* install under /usr/local), not Debian's /usr/bin/php*.
ENV PATH="/usr/local/bin:/usr/local/sbin:/usr/sbin:/usr/bin:/sbin:/bin"

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y tzdata
ENV TZ=Europe/Budapest
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt update && apt -y install netcat-openbsd zlib1g-dev libpng-dev libjpeg-dev
RUN docker-php-ext-configure gd --with-jpeg && docker-php-ext-install mysqli pdo pdo_mysql gd && a2enmod rewrite headers
# ImageMagick CLI + PHP Imagick (PECL). Fail build if imagick does not load for /usr/local/bin/php.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        imagemagick \
        libmagickwand-dev \
        autoconf \
        g++ \
        make \
        pkg-config; \
    pecl install imagick; \
    docker-php-ext-enable imagick; \
    EXT_DIR=$(php -r "echo ini_get('extension_dir');"); \
    test -f "${EXT_DIR}/imagick.so"; \
    printf "extension=%s/imagick.so\n" "${EXT_DIR}" > /usr/local/etc/php/conf.d/docker-php-ext-imagick.ini; \
    /usr/local/bin/php -m | grep -i imagick; \
    /usr/local/bin/php -r "extension_loaded('imagick') || exit(1);"; \
    rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN chmod +x ./docker/app/entrypoint.sh

ENTRYPOINT [ "./docker/app/entrypoint.sh" ]
