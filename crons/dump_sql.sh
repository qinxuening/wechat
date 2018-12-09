#!/bin/bash
. ./host.sh
cron=${webhttp}"/cronapi/mysqldump/dumpdata"
T=$(date +%H:%M)
/usr/bin/curl --insecure ${cron}
echo $T
exit 0