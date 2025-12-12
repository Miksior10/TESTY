FROM php:8.2-apache

RUN a2enmod rewrite \
  && docker-php-ext-install mysqli pdo pdo_mysql

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf \
 && echo '<Directory ${APACHE_DOCUMENT_ROOT}>' >> /etc/apache2/sites-available/000-default.conf \
 && echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
 && echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf

COPY php/php.ini /usr/local/etc/php/conf.d/app.ini


