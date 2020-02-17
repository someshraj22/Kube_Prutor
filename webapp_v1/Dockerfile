# Web Application for ITS
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use Local Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Configure mirror
RUN		apt-get update && apt-get install -y software-properties-common git-core openssh-client supervisor curl php5-cli php5-curl vim
RUN		add-apt-repository -y ppa:chris-lea/node.js

# Install necessary tools
RUN		apt-get update && apt-get install -y nodejs
RUN		npm install -g forever
RUN		echo "Asia/Kolkata" > /etc/timezone
RUN		dpkg-reconfigure -f noninteractive tzdata

# Copy the global deploy keys
RUN             mkdir /root/.ssh
COPY            id_rsa* /root/.ssh/
RUN             chmod 600 /root/.ssh/id_rsa
COPY            config /root/.ssh/config

# Copy required files
COPY            supervisord.conf /etc/supervisord.conf
COPY		node-init /usr/bin/node-init
COPY		node-restart /usr/bin/node-restart
COPY		env.ini /tmp/env.ini

RUN             cat /tmp/env.ini >> /etc/environment

# clone git hub
RUN		mkdir -p /var/www
RUN		git clone git@bitbucket.org:someshraj22/webapp.git /root/webapp

RUN		cp -r /root/webapp/* /var/www/
# Set the volume path in container
VOLUME          ["/var/www", "/root"]

# Configure startup for web application
EXPOSE		3000 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT	["/usr/bin/node-init"]

