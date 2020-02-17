# Engine Component
# Created On: 19-04-2016
FROM            ubuntu:14.04
MAINTAINER      Amey Karkare <karkare@iitk.ac.in>

# Use local Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Install required tools
RUN             apt-get update && apt-get install -y apache2 

RUN             locale-gen en_US en_US.UTF-8 && dpkg-reconfigure locales 
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

COPY            node-init /usr/bin/node-init

# Configure Apache
RUN             echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY            000-default.conf /etc/apache2/sites-available/000-default.conf
COPY            mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
RUN             a2enmod rewrite

# Set the volume path in container
VOLUME          ["/var/www", "/var/log/apache2"]

# Configure startup for engine
EXPOSE          80 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT      ["/usr/bin/node-init"]
