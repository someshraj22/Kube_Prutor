# Testing OS component for ITS
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use Local Mirror
COPY		sources.list /etc/apt/sources.list

# Install necessary tools
RUN		apt-get update && apt-get install -y openssh-client git-core vim php5-cli
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata
RUN		touch /var/log/syslog

# Copy the global deploy keys
RUN             mkdir /root/.ssh
COPY            id_rsa* /root/.ssh/
RUN             chmod 600 /root/.ssh/id_rsa
COPY            config /root/.ssh/config

# Configure to run
EXPOSE		22
ENTRYPOINT	["tail", "-f", "/var/log/syslog"]
