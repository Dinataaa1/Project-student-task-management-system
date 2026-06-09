FROM php:8.2-apache

# Install PHP extensions required for MySQL connection.
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Raise PHP upload limits for room photo uploads.
RUN { \
	echo 'file_uploads=On'; \
	echo 'upload_max_filesize=20M'; \
	echo 'post_max_size=24M'; \
	echo 'max_file_uploads=20'; \
	echo 'memory_limit=256M'; \
} > /usr/local/etc/php/conf.d/uploads.ini

# Enable Apache rewrite for cleaner URL handling if needed.
RUN a2enmod rewrite

WORKDIR /var/www/html
