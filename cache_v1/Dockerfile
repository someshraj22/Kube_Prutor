# Memcached component for ITS.
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use Local mirror (Optional)
COPY		sources.list /etc/apt/sources.list

# Install necessary tools
RUN		apt-get update && apt-get install -y memcached supervisor telnet
RUN		sed -i 's/127.0.0.1/0.0.0.0/g' /etc/memcached.conf
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

# Copy required files
COPY            supervisord.conf /etc/supervisord.conf

# Create a volume for debugging purposes
VOLUME          ["/var/log"]

# Configure to run at startup
EXPOSE		11211 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT	["supervisord", "-c", "/etc/supervisord.conf"]
