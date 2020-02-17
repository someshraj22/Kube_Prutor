# Web Application for compiler feedback
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use local mirror (Optional)
# COPY            sources.list /etc/apt/sources.list

# Install required tools
RUN             apt-get update && apt-get install -y apache2 php5 php5-dev php5-mysql php-pear libv8-dev supervisor vim g++ cpp
RUN				pecl install v8js-0.1.3
RUN             locale-gen en_US en_US.UTF-8 && dpkg-reconfigure locales
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

# Configure Apache and V8JS
RUN             echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY            ./site.conf /etc/apache2/sites-available/000-default.conf
RUN             a2enmod rewrite
RUN		echo "extension=v8js.so" > /etc/php5/mods-available/v8js.ini
RUN		ln -s /etc/php5/mods-available/v8js.ini /etc/php5/apache2/conf.d/20-v8js.ini
RUN		ln -s /etc/php5/mods-available/v8js.ini /etc/php5/cli/conf.d/20-v8js.ini

# Copy codebase
RUN		rm -rf /var/www/html
COPY		./app/ /var/www/

# Copy environment
COPY            env.ini /tmp/env.ini
RUN             cat /tmp/env.ini >> /etc/environment

# Copy init script
COPY		./node-init /usr/bin/node-init

VOLUME          ["/var/www", "/var/log/apache2"]

EXPOSE          80

ENTRYPOINT      ["/usr/bin/node-init"]
