<?php


//一、Centos 7.x yum安装php5.6.X（最新版）
   https://blog.csdn.net/TH_NUM/article/details/78195568

1.检查当前安装的PHP包
yum list installed | grep php
如果有安装的PHP包，先删除他们
yum remove php.x86_64 php-cli.x86_64 php-common.x86_64 php-gd.x86_64 php-ldap.x86_64 php-mbstring.x86_64 php-mcrypt.x86_64 php-mysql.x86_64 php-pdo.x86_64  

2.配置yum源 
CentOS 7.0的源。
yum install epel-release
rpm -ivh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm


3.使用yum list命令查看可安装的包(Packege)
yum list --enablerepo=remi --enablerepo=remi-php56 | grep php

4.安装PHP5.6.x
yum源配置好了，下一步就安装PHP5.6。
yum install --enablerepo=remi --enablerepo=remi-php56 php php-opcache php-devel php-mbstring php-mcrypt php-mysqlnd php-phpunit-PHPUnit php-pecl-xdebug php-pecl-xhprof

用PHP命令查看版本。
php --version
安装PHP-fpm
yum install --enablerepo=remi --enablerepo=remi-php56 php-fpm 

systemctl enable php-fpm 
 
5.设置上传大小：
    upload_max_filesize = 120M
    upload_max_filesize = 120M
//二、安装httpd  
//yum install httpd
//systemctl enable httpd
//让apache去解析PHP文件，在apache配置文件httpd.conf中修改：
//1、在<IfModule dir_module>添加index.php
<IfModule dir_module>
   DirectoryIndex index.html index.php
</IfModule>


//2、<IfModule mime_module> 添加Addtype application/x-httpd-php .php .phtml
<IfModule mime_module>
	Addtype application/x-httpd-php .php .phtml
</IfModule>


//3、安装php-redis、php-memcached、php-memcache
yum install --enablerepo=remi --enablerepo=remi-php56  php-redis php-memcached、php-memcache


//mysql安装
MySQL 社区仓库
http://repo.mysql.com/

1 yum update

2 wget http://repo.mysql.com/mysql57-community-release-el7-8.noarch.rpm

3 yum install mysql57-community-release-el7-8.noarch.rpm

4 sudo yum update

5 yum install mysql-server

6 systemctl start mysqld

mysql更改密码(密码复杂度为数字、字母大小写、其他字符)
1. Stop mysql:
systemctl stop mysqld

2. Set the mySQL environment option 
systemctl set-environment MYSQLD_OPTS="--skip-grant-tables"

3. Start mysql usig the options you just set
systemctl start mysqld

4. Login as root
mysql -u root

5. Update the root user password with these mysql commands
mysql> UPDATE mysql.user SET authentication_string = PASSWORD('MyNewPassword')
    -> WHERE User = 'root' AND Host = 'localhost';
mysql> FLUSH PRIVILEGES;
mysql> quit

*** Edit ***
As mentioned my shokulei in the comments, for 5.7.6 and later, you should use 
   mysql> ALTER USER 'root'@'localhost' IDENTIFIED BY 'MyNewPass';
Or you will get a warning

6. Stop mysql
systemctl stop mysqld

7. Unset the mySQL envitroment option so it starts normally next time
systemctl unset-environment MYSQLD_OPTS

8. Start mysql normally:
systemctl start mysqld

Try to login using your new password:
7. mysql -u root -p  --default-character-set=utf8


创建数据库：
CREATE DATABASE `mars` CHARACTER SET utf8 COLLATE utf8_general_ci;

设置编码连接
mysql -uroot -p314233qxnQXN,  --default-character-set=utf8

导入：
source /var/www/mars.sql;
查看端口号：show global variables like 'port';
查看存储路径：show global variables like "%datadir%";

支持root用户允许远程连接mysql数据库
grant all privileges on *.* to 'root'@'%' identified by ’314233qxnQXN?’ with grant option;
flush privileges;

运行mysql时，提示Table ‘performance_schema.session_variables’ doesn’t exist
mysql_upgrade -u root -p --force
重启

mysqldump --default-character-set=utf8 -uroot -p314233qxnQXN, scan> scan.sql
    发生警告：[Warning] Using a password on the command line interface can be insecure.
         解决方案：
    vim /etc/my.cmf
    添加：
    [mysqldump]
        user=root
        password=314233qxnQXN,
        

安装redis：
yum install redis 

添加密码验证：
requirepass 123456qxn
修改端口：
port 6380
允许远程：
#bind 127.0.0.1

redis-cli -h 127.0.0.1 -p 6379 -a 123456qxn



队列守护进程执行：
nohup php think queue:work --queue emailJobQueue --daemon >/dev/null 2>&1

php+redis 出现Permission denied错误：
设置vim /etc/sysconfig/selinux：
SELINUX=enforcing 改为 SELINUX=disabled
重启服务reboot

设置开机启动脚本：
vim /etc/rc.local

添加环境变量：
vim /etc/profile
export PATH=$PATH:/var/www/html/tp5
source /etc/profile


//启动队列脚本queue.sh
#!/bin/bash
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin:/var/www/html/tp5
cd /var/www/html/tp5
export PATH
echo 'start queue'
nohup php think queue:work --queue emailJobQueue --daemon >/dev/queue.log >/dev/null 2>&1 &
echo 'start ok'


判断进程是否存在，不存在启动进程start.sh：
#!/bin/bash
#ps -fe|grep 'php think queue:work --queue emailJobQueue --daemon'
PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
export PATH
count=`ps -ef | grep emailJobQueue | grep -v "grep" | wc -l`
if [ $count ==  0 ]
then
        echo "start process....."
         /var/www/html/tp5/queue.sh
        #nohup php think queue:work --queue emailJobQueue --daemon >/dev/null 2>&1
else
        echo "runing....."
fi

定时扫描进程：
crontab -e
vim /etc/crontab
*/2 * * * *  /shell/start.sh
*/2 * * * *  /var/www/html/wechat/crons/work_check.sh


坏的解释器: 没有那个文件或目录
    解决：sed -i 's/\r$//' dump_sql.sh

修改web目录所属权限：
    chown -R apache:apache html

linux查看当前目录下文件大小：
    ls -alh
    
linux查看文件倒数第几行：
    tail -n scan.sql
    
linux查看文件文件第几行：
    head -1 scan.sql
    
linux全局搜索某个目录下的文字对应文件：
    grep -r "abc" ./

apache apache 执行shell权限
    vim /etc/sudoers
    apache ALL=(ALL) NOPASSWD: ALL
    
























































