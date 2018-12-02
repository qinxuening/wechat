#!/bin/bash
. ./host.sh
#webhttp="http://localhost"
echo $webhttp
cron=${webhttp}"/cronapi/get_info/getResult"

T=$(date +%H:%M)
int=1;
while(( $int<=60 ));
do
	/usr/bin/curl --insecure ${cron}
    let "int++";
    sleep 30;
done
echo $T >> /dev/null
exit 0
