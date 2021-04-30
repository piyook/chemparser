FROM php:8-apache
 
# Update web root to public
# See: https://hub.docker.com/_/php#changing-documentroot-or-other-apache-configuration

#set working directory to /var/www/html
WORKDIR /var/www/html

#copy existing bound files to image
## COPY /src/vapp/dist/ ./public

#COPY PHP Code into PHP Folder

## COPY /src/form ./form

#set the environment variable to the desired public web root - in this case public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# use sed to search and replace the webroot in the relevant conf files.
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable mod_rewrite
RUN a2enmod rewrite

## create a non-root user for security in production
RUN useradd -ms /bin/bash apache
USER apache