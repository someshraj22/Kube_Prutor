#!/bin/sh
rdb_addr=$(docker inspect --format '{{ .NetworkSettings.IPAddress }}' rdb)
# use a READONLY account for mysql db
db_user=backup
db_pass=backitup

x=`date +"%Y%m%d-%H%M%S"`
mysqldump -P 3306 -h ${rdb_addr} -u ${db_user} -p${db_pass} its > its`echo $x`.sql
if [ $? ]; 
    then
	gzip its`echo $x`.sql;
    else
	echo "backup-its.sh: mysqldump failed!!!"
fi
