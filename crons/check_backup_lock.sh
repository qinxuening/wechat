#!/bin/bash
. ./host.sh
#ls | grep -E '^[A-Za-z0-9]+_[0-9]{14}\.sql$'
#file=${databasedir}/backup.lock
#if [ -f ${file} ];then
#	echo "文件存在"
#	rm -f ${file}
#else
#	echo "文件不存在"
#fi
cron=${webhttp}"/cronapi/Mysqldump/checkBackupLock"
#result=""
#pid=""
#echo ${cron}
T=$(date +%H:%M)
i=1
while((1))
do
	/usr/bin/curl --insecure ${cron}
	result=`tail ../crons/logs_backup.txt`
	echo $T >> logs.txt
	if [ $result == 'true' ];then
	    rm -rf ../database/backup.lock
		echo ${result};
		pid=`ps -ef | grep check_backup_lock.sh |grep -v grep | awk '{print $2}'`
		#if [-n "$pid"];then
		echo ${pid}
		kill -9 ${pid}
		#fi
	else
		echo 'false'
	fi
	let "i++";
    sleep 1;
done
echo $T >> /dev/null
exit 0
