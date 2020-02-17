# Database Component
# Created On: 19-04-2016
FROM		ubuntu:14.04
MAINTAINER	Amey Karkare <karkare@iitk.ac.in>

# Use Local Mirror (Optional)
COPY            sources.list /etc/apt/sources.list

# Install required tools
RUN             apt-get update && apt-get install -y mysql-server supervisor apache2 php5 php5-mysql php5-mcrypt vim
RUN             echo "Asia/Kolkata" > /etc/timezone
RUN             dpkg-reconfigure -f noninteractive tzdata

# Copy schema and install database.
COPY		schema.sql /tmp/schema.sql
### User with ALL accesses (prutor/toor)
RUN		service mysql start && mysql < /tmp/schema.sql && mysql -e "CREATE USER 'prutor'@'%' IDENTIFIED BY 'toor'"
RUN		service mysql start && mysql -e "GRANT ALL ON its.* TO 'prutor'@'%'"
### User with readonly acces (backup/backitup)
#RUN		service mysql start && mysql -e "CREATE USER 'backup'@'%' IDENTIFIED BY 'backitup'" && mysql -e "GRANT SELECT, SHOW VIEW, LOCK TABLES ON its.* TO 'backup'@'%'"
### Enable logging to start with
RUN 		service mysql start && mysql its -e "INSERT INTO configuration (section, setting, value) VALUES ('TOOLS', 'LOGGING', '1');"

RUN		service mysql start && apt-get install -y phpmyadmin
RUN		ln -s /etc/phpmyadmin/apache.conf /etc/apache2/sites-enabled/phpmyadmin.conf

# Copy required files

COPY            supervisord.conf /etc/supervisord.conf
COPY		node-init /usr/bin/node-init
COPY		index.php /var/www/html/index.php
RUN		rm /var/www/html/index.html

# Allow listening outside of container
RUN		sed -i 's/bind-address/#bind-address/g' /etc/mysql/my.cnf

# Add guest user

RUN		service mysql start && mysql its -e "INSERT INTO account (id,email,password,name,section,type,admin_role,enabled) SELECT '6abc1812-bddd-11e4-afe9-bcee7bdb59fb','guest@prutor.edu',REPLACE('#2a#10#MGo.DISeTrzvWIRXDqPfruP24kDJ8.K0xpbaWaqJQ6xrU911Oj6Jy','#','\$'),'Guest User','X1','ADMIN',0,1"

# Register volumes for external access
VOLUME		["/var/log/mysql", "/var/lib/mysql"]

# Configure startup for database
EXPOSE 		3306 8300 8301 8301/udp 8302 8302/udp 8400 8500 53/udp
ENTRYPOINT	["/usr/bin/node-init"]
