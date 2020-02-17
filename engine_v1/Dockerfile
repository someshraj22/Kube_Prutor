# Engine Component
# Created On: 19-04-2016
FROM            ubuntu:14.04
MAINTAINER      Amey Karkare <karkare@iitk.ac.in>

# Use local Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Install required tools
RUN             apt-get update && apt-get install -y build-essential apache2 php5 php5-mysql php5-memcached git-core openssh-client supervisor curl php5-curl vim php5-dev libcurl4-openssl-dev libpcre3-dev 
# Optional Python Libraries: Uncomment if needed.
#RUN             apt-get update && apt-get install -y python-dev libpng12-dev libfreetype6-dev libblas-dev liblapack-dev libatlas-base-dev gfortran python-pip
#RUN             pip install numpy matplotlib scipy

#RUN             pecl install mongo
RUN		pecl install mongodb-1.1.9
RUN             ln -s /etc/php5/mods-available/mongodb.ini /etc/php5/cli/conf.d/20-mongodb.ini
RUN             ln -s /etc/php5/mods-available/mongodb.ini /etc/php5/apache2/conf.d/20-mongodb.ini
RUN             locale-gen en_US en_US.UTF-8 && dpkg-reconfigure locales 
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

#Installing Firejail
WORKDIR         /tmp
RUN             git clone https://github.com/netblue30/firejail
WORKDIR         /tmp/firejail
#RUN             ./configure && make && make install-strip

# Installing LibSandbox
WORKDIR         /tmp
RUN             git clone https://github.com/openjudge/sandbox.git
WORKDIR         /tmp/sandbox/libsandbox
RUN             ./configure
RUN             make install
RUN             ln -s /usr/local/lib/libsandbox.so /usr/lib/libsandbox.so

# Install compilers : C, Java
RUN             apt-get install -y clang openjdk-7-jdk openjdk-7-jre

# Installing pypy-sandbox
RUN             apt-get install -y python-pypy.sandbox

# Copy required files
COPY            supervisord.conf /etc/supervisord.conf
COPY            node-init /usr/bin/node-init
COPY            env.ini /tmp/env.ini
COPY            mongodb.ini /etc/php5/mods-available/mongodb.ini

# Configure Apache
RUN             echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY            000-default.conf /etc/apache2/sites-available/000-default.conf
COPY            mpm_prefork.conf /etc/apache2/mods-available/mpm_prefork.conf
RUN             a2enmod rewrite
RUN             cat /tmp/env.ini >> /etc/environment

# Copy the global deploy keys
RUN             mkdir /root/.ssh
COPY            id_rsa* /root/.ssh/
RUN             chmod 600 /root/.ssh/id_rsa
COPY            config /root/.ssh/config


# deploy wali command
RUN		git clone git@bitbucket.org:someshraj22/engine.git /root/engine
RUN		cp -r /root/engine/* /var/www/
RUN		mkdir -p /var/www/data/tmp
RUN		mkdir -p /var/www/logs
RUN		chmod -R 755 /var/www
RUN		chmod 733 /var/www/data /var/www/data/tmp /var/www/logs
RUN		/var/www/composer.phar --working-dir=/var/www self-update
RUN		/var/www/composer.phar --working-dir=/var/www install
RUN		cp /var/www/app/compilers/sandbox /var/www/data/sandbox
RUN		cp /var/www/app/external/sandbox  /var/www/data/cpsandbox
# Set the volume path in container
VOLUME          ["/var/www", "/var/log/apache2"]

# Configure startup for engine
EXPOSE          80 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT      ["/usr/bin/node-init"]
