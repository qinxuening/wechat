#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin:/var/www/html/wechat
cd /var/www/html/wechat
export PATH
echo 'start queue'
nohup php think queue:work --queue emailJobQueue --daemon >/dev/queue.log >/dev/null 2>&1 &
echo 'start ok' 
