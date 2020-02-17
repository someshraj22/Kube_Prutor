#Visualizer For code execution ITS
#Created on: 20-06-2017
FROM ubuntu:14.04
MAINTAINER Amey Karkare <karkare@iitk.ac.in>

# Use Local Mirror (Optional)
COPY sources.list /etc/apt/sources.list

# Install necessary tools
RUN apt-get update && apt-get install -y python python-setuptools python-dev build-essential autotools-dev automake libc6-dbg supervisor curl php5-cli php5-curl vim
RUN easy_install pip
RUN pip install bottle
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN pip install gunicorn
RUN pip install gevent

#Copy required files
COPY            supervisord.conf /etc/supervisord.conf
COPY		node-init /usr/bin/node-init
COPY 		v5-unity /v5-unity
RUN		cd /v5-unity/valgrind-3.11.0 && make clean && ./configure --prefix=`pwd`/inst && make && make install && cd /

EXPOSE 		8003 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp

ENTRYPOINT ["/usr/bin/node-init"]
