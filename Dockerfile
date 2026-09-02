FROM php:8.3-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY backend/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN echo '<Directory /var/www/html/src/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/app.conf

RUN a2enconf app

ENV APACHE_DOCUMENT_ROOT=/var/www/html/src/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

EXPOSE 80