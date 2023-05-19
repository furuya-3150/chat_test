FROM php:8.2.1-apache

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Enable Apache mod_rewrite
RUN a2enmod rewrite

EXPOSE 80

