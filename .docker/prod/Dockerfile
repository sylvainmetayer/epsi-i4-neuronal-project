FROM php:7.2-apache as installer

RUN apt-get update && apt-get install -y \
        libpng-dev \
        git subversion mercurial bash patch


RUN docker-php-ext-install gd && docker-php-ext-enable gd

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.7.2

RUN curl --silent --fail --location --retry 3 --output /tmp/installer.php --url https://raw.githubusercontent.com/composer/getcomposer.org/b107d959a5924af895807021fcef4ffec5a76aa9/web/installer \
        && php -r " \
                \$signature = '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061'; \
                \$hash = hash('SHA384', file_get_contents('/tmp/installer.php')); \
                if (!hash_equals(\$signature, \$hash)) { \
                        unlink('/tmp/installer.php'); \
                        echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
                        exit(1); \
                }" \
        && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
        && composer --ansi --version --no-interaction \
        && rm -rf /tmp/* /tmp/.htaccess


ENV ENVIRONMENT INSTALLER

WORKDIR /var/www/

COPY ./app /var/www/

RUN composer install --no-ansi --no-dev --no-interaction --no-scripts --optimize-autoloader

RUN find /var/www/ -maxdepth 1 -type f -delete
RUN rm -R /var/www/tests

FROM php:7.2-apache

COPY --from=installer /var/www/ /var/www/

RUN apt-get update && apt-get install -y \
        libpng-dev \
    && docker-php-ext-install gd && docker-php-ext-enable gd \
    && chown -R www-data /var/www/

ENV ENVIRONMENT PROD

WORKDIR /var/www/