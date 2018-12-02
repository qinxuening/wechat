#!/bin/bash

webhttp="http://localhost" #marip

cron=${webhttp}"/mare/index.php/sapi/GetAiinfo/getResult"

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
