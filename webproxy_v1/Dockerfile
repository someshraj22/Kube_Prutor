# HAProxy Component
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use IITK Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Install required tools
RUN             apt-get update && apt-get install -y haproxy git-core wget supervisor curl unzip php5-cli php5-curl socat
RUN		sed -i 's/ENABLED=0/ENABLED=1/g' /etc/default/haproxy
RUN		sed -i 's/#$ModLoad imudp/$ModLoad imudp/g' /etc/rsyslog.conf
RUN             sed -i 's/#$UDPServerRun 514/$UDPServerRun 514\n$UDPServerAddress 127.0.0.1/g' /etc/rsyslog.conf
RUN		sed -i 's/daemon/#daemon/g' /etc/haproxy/haproxy.cfg
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

# Copy required files
COPY		whitelist.lst /etc/haproxy/whitelist.lst
COPY		haproxy.tmpl /etc/haproxy/haproxy.tmpl
COPY		supervisord.conf /etc/supervisord.conf
COPY		node-init /usr/bin/node-init
COPY		haproxy.default	/tmp/haproxy.default
COPY		updater.php /root/updater.php

# Configure
RUN		touch /var/log/syslog
RUN		cat /tmp/haproxy.default >> /etc/haproxy/haproxy.cfg

# Create a volume for debugging purposes
VOLUME          ["/var/log"]

# Configure startup for proxy
EXPOSE   	80 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT      ["/usr/bin/node-init"]
