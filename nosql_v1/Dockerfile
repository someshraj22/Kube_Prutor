# NoSQL component
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use Local Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Install required packages
RUN		apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
RUN		echo "deb http://repo.mongodb.org/apt/ubuntu "$(lsb_release -sc)"/mongodb-org/3.0 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-3.0.list
RUN		apt-get update && apt-get install -y --force-yes supervisor vim mongodb-org
RUN		mkdir -p /data/db
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata
RUN		sed -i 's/bind_ip = 127.0.0.1/#bind_ip = 127.0.0.1/g' /etc/mongod.conf

# Copy required files

COPY		init_mongo.js /tmp/init_mongo.js
COPY		user.js /tmp/user.js
COPY            supervisord.conf /etc/supervisord.conf
COPY            node-init /usr/bin/node-init

RUN mongod --fork --logpath /var/log/mongodb.log \
    && sleep 5 && mongo its /tmp/user.js 

RUN mongod --fork --logpath /var/log/mongodb.log \
    && sleep 5 && mongo its /tmp/init_mongo.js 
#RUN		mongo show dbs
#RUN		mongo --fork \ && sleep 5 \&& mongo its /tmp/user.js
#RUN		mongo --fork --logpath /var/log/mongo/tmp/user.js
#RUN		mongo its /tmp/init_mongo.js

EXPOSE		27017 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp

ENTRYPOINT	["/usr/bin/node-init"]
